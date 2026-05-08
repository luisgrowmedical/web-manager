<?php
/**
 * Site Controller
 */

class SiteController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function index() {
        $user_id = $_SESSION['user_id'];
        $is_admin = ($_SESSION['role'] === 'admin');

        // Search & Filter
        $search = $_GET['search'] ?? '';
        $country = $_GET['country'] ?? '';
        $state = $_GET['state'] ?? '';
        $city = $_GET['city'] ?? '';
        $specialty = $_GET['specialty'] ?? '';
        
        // Sorting
        $sort_by = $_GET['sort_by'] ?? 'name';
        $order = $_GET['order'] ?? 'ASC';
        $allowed_sorts = ['name', 'url', 'pages_count', 'posts_count', 'drafts_count', 'site_weight', 'images_count', 'sync_date'];
        if (!in_array($sort_by, $allowed_sorts)) $sort_by = 'name';
        $order = (strtoupper($order) === 'DESC') ? 'DESC' : 'ASC';

        $query = "
            SELECT s.id, s.name, s.url, s.api_key, s.country, s.state, s.city, s.specialty, s.last_sync,
                   m.pages_count, m.posts_count, m.drafts_count, m.site_weight, m.images_count, m.updates_count, m.sync_date
            FROM sites s 
            LEFT JOIN (
                SELECT m1.* 
                FROM site_metrics m1
                INNER JOIN (
                    SELECT site_id, MAX(id) as metric_id
                    FROM site_metrics 
                    GROUP BY site_id
                ) m2 ON m1.id = m2.metric_id
            ) m ON s.id = m.site_id
        ";

        $params = [];
        $where_clauses = [];

        if (!$is_admin) {
            $where_clauses[] = "s.id IN (SELECT site_id FROM site_user_access WHERE user_id = ?)";
            $params[] = $user_id;
        }

        if ($search) {
            $where_clauses[] = "(s.name LIKE ? OR s.url LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        if ($country) {
            $where_clauses[] = "s.country = ?";
            $params[] = $country;
        }

        if ($state) {
            $where_clauses[] = "s.state = ?";
            $params[] = $state;
        }

        if ($city) {
            $where_clauses[] = "s.city = ?";
            $params[] = $city;
        }

        if ($specialty) {
            $where_clauses[] = "s.specialty = ?";
            $params[] = $specialty;
        }

        if ($where_clauses) {
            $query .= " WHERE " . implode(" AND ", $where_clauses);
        }

        // Pagination
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $count_query = "SELECT COUNT(s.id) FROM sites s";
        if ($where_clauses) {
            $count_query .= " WHERE " . implode(" AND ", $where_clauses);
        }
        $count_stmt = $this->db->prepare($count_query);
        $count_stmt->execute($params);
        $total_records = $count_stmt->fetchColumn();
        $total_pages = ceil($total_records / $limit);

        $query .= " ORDER BY $sort_by $order LIMIT $limit OFFSET $offset";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $sites = $stmt->fetchAll();

        // Get unique filter values for dropdowns
        $countries = $this->db->query("SELECT DISTINCT country FROM sites WHERE country IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN);
        $specialties = $this->db->query("SELECT DISTINCT specialty FROM sites WHERE specialty IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN);

        view('sites/list', [
            'title' => 'Connected Sites',
            'sites' => $sites,
            'countries' => $countries,
            'specialties' => $specialties,
            'page' => $page,
            'total_pages' => $total_pages,
            'total_records' => $total_records,
            'filters' => [
                'search' => $search,
                'country' => $country,
                'state' => $state,
                'city' => $city,
                'specialty' => $specialty,
                'sort_by' => $sort_by,
                'order' => $order
            ]
        ]);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $url = $_POST['url'];
            $api_key = $_POST['api_key'];
            $country = $_POST['country'] ?? null;
            $state = $_POST['state'] ?? null;
            $city = $_POST['city'] ?? null;
            $specialty = $_POST['specialty'] ?? null;

            $stmt = $this->db->prepare("INSERT INTO sites (name, url, api_key, country, state, city, specialty) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $url, $api_key, $country, $state, $city, $specialty]);
            
            set_message("Site connected successfully!");
            redirect('/web-manager/public/index.php?route=sites');
        }
        view('sites/add', ['title' => 'Connect New Site']);
    }

    public function view($id) {
        $stmt = $this->db->prepare("SELECT * FROM sites WHERE id = ?");
        $stmt->execute([$id]);
        $site = $stmt->fetch();

        if (!$site) redirect('/web-manager/public/index.php?route=sites');

        $stmt = $this->db->prepare("SELECT * FROM site_metrics WHERE site_id = ? ORDER BY sync_date DESC, id DESC LIMIT 1");
        $stmt->execute([$id]);
        $metrics = $stmt->fetch();

        view('sites/detail', [
            'title' => 'Site Detail: ' . $site['name'],
            'site' => $site,
            'metrics' => $metrics
        ]);
    }

    public function sync($id, $is_internal = false) {
        $stmt = $this->db->prepare("SELECT * FROM sites WHERE id = ?");
        $stmt->execute([$id]);
        $site = $stmt->fetch();

        if (!$site) {
            if ($is_internal) return;
            redirect('/web-manager/public/index.php?route=sites');
        }

        // Call WordPress Plugin API (Native REST API Route)
        $api_url = $site['url'] . '/wp-json/web-manager/v1/stats?api_key=' . urlencode($site['api_key']);
        
        $ch = curl_init($api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-API-KEY: ' . $site['api_key']
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For local development (MAMP)
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        $status = 'error';
        if ($http_code === 200) {
            $site_data = json_decode($response, true);
            
            if ($site_data && isset($site_data['site_url'])) {
                // Save metrics
                $stmt = $this->db->prepare("
                    INSERT INTO site_metrics 
                    (site_id, pages_count, posts_count, drafts_count, active_theme, themes_count, active_plugins_count, total_plugins_count, site_weight, images_count, updates_count) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $id,
                    $site_data['pages'],
                    $site_data['posts'],
                    $site_data['drafts'],
                    $site_data['themes']['active'],
                    $site_data['themes']['total'],
                    $site_data['plugins']['active'],
                    $site_data['plugins']['total'],
                    $site_data['weight'],
                    $site_data['images'],
                    $site_data['updates'] ?? 0
                ]);

                // Update site last sync
                $stmt = $this->db->prepare("UPDATE sites SET last_sync = CURRENT_TIMESTAMP WHERE id = ?");
                $stmt->execute([$id]);
                
                $status = 'success';
                set_message("Site syncronized successfully!");
            } else {
                set_message("API returned an invalid response.", "error");
            }
        } else {
            set_message("Failed to connect to site (HTTP $http_code). " . $curl_error, "error");
        }

        // Log API attempt
        $stmt = $this->db->prepare("INSERT INTO api_logs (site_id, action, status, response) VALUES (?, 'sync', ?, ?)");
        $stmt->execute([$id, $status, $response]);

        if (!$is_internal) {
            redirect('/web-manager/public/index.php?route=sites&action=view&id=' . $id);
        }
    }

    public function delete($id) {
        require_admin();
        $stmt = $this->db->prepare("DELETE FROM sites WHERE id = ?");
        $stmt->execute([$id]);
        redirect('/web-manager/public/index.php?route=sites');
    }
}
