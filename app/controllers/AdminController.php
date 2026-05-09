<?php
/**
 * Admin Controller
 */

class AdminController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function index() {
        $this->settings();
    }

    public function settings() {
        $users = $this->load_users();
        $roles = $this->load_roles();
        $sites = $this->load_sites_with_connector_versions();

        view('admin/settings', [
            'title' => 'Settings',
            'users' => $users,
            'roles' => $roles,
            'connector_update' => $this->build_connector_update_status($sites)
        ]);
    }

    public function users() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $role_id = $_POST['role_id'];
            $avatar_path = null;

            // Handle Avatar Upload
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $filename = $_FILES['avatar']['name'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                
                if (in_array($ext, $allowed)) {
                    $new_name = 'avatar_' . time() . '_' . $username . '.' . $ext;
                    
                    // Use absolute-like path from the project root
                    $target_dir = __DIR__ . '/../../public/uploads/avatars/';
                    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
                    
                    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $target_dir . $new_name)) {
                        $avatar_path = '/web-manager/public/uploads/avatars/' . $new_name;
                    }
                }
            }

            try {
                $stmt = $this->db->prepare("INSERT INTO users (username, email, password, role_id, avatar) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$username, $email, $password, $role_id, $avatar_path]);
                
                set_message("User created successfully!");
                redirect('/web-manager/public/index.php?route=settings');
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    set_message("Username or Email already exists.", "error");
                } else {
                    set_message("Database error: " . $e->getMessage(), "error");
                }
            }
        }

        redirect('/web-manager/public/index.php?route=settings');
    }

    public function manageAccess($user_id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Clear existing access
            $stmt = $this->db->prepare("DELETE FROM site_user_access WHERE user_id = ?");
            $stmt->execute([$user_id]);

            // Add new access
            if (isset($_POST['sites'])) {
                $stmt = $this->db->prepare("INSERT INTO site_user_access (user_id, site_id) VALUES (?, ?)");
                foreach ($_POST['sites'] as $site_id) {
                    $stmt->execute([$user_id, $site_id]);
                }
            }
            redirect('/web-manager/public/index.php?route=settings');
        }

        $stmt = $this->db->query("SELECT * FROM sites");
        $all_sites = $stmt->fetchAll();

        $stmt = $this->db->prepare("SELECT site_id FROM site_user_access WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $current_access = $stmt->fetchAll(PDO::FETCH_COLUMN);

        view('admin/access', [
            'title' => 'Manage Site Access for ' . $user['username'],
            'user' => $user,
            'all_sites' => $all_sites,
            'current_access' => $current_access
        ]);
    }

    public function deleteUser($id) {
        require_admin();
        
        // Prevent deleting yourself
        if ($id == $_SESSION['user_id']) {
            set_message("You cannot delete your own account!", "error");
            redirect('/web-manager/public/index.php?route=settings');
        }

        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        
        set_message("User deleted successfully!");
        redirect('/web-manager/public/index.php?route=settings');
    }

    private function load_users() {
        $stmt = $this->db->query("
            SELECT u.*, r.name as role_name 
            FROM users u 
            JOIN roles r ON u.role_id = r.id
            ORDER BY u.username ASC
        ");

        return $stmt->fetchAll();
    }

    private function load_roles() {
        $stmt = $this->db->query("SELECT * FROM roles ORDER BY name ASC");

        return $stmt->fetchAll();
    }

    private function load_sites_with_connector_versions() {
        $has_connector_version = $this->site_metrics_has_column('connector_version');
        $connector_version_select = $has_connector_version ? ', m.connector_version' : ', NULL AS connector_version';
        $connector_version_inner_select = $has_connector_version ? ', m1.connector_version' : '';

        $stmt = $this->db->query("
            SELECT s.* {$connector_version_select}
            FROM sites s
            LEFT JOIN (
                SELECT m1.site_id {$connector_version_inner_select}
                FROM site_metrics m1
                INNER JOIN (
                    SELECT site_id, MAX(id) as metric_id
                    FROM site_metrics 
                    GROUP BY site_id
                ) m2 ON m1.id = m2.metric_id
            ) m ON s.id = m.site_id
            ORDER BY s.name ASC
        ");

        return $stmt->fetchAll();
    }

    private function site_metrics_has_column($column) {
        static $columns = null;

        if ($columns === null) {
            $stmt = $this->db->query("SHOW COLUMNS FROM site_metrics");
            $columns = array_column($stmt->fetchAll(), 'Field');
        }

        return in_array($column, $columns, true);
    }

    private function build_connector_update_status($sites) {
        $available_version = $this->connector_available_version();
        $items = [];
        $outdated = 0;
        $unknown = 0;

        foreach ($sites as $site) {
            $installed_version = $site['connector_version'] ?? null;
            $status = 'ok';

            if (!$installed_version) {
                $status = 'unknown';
                $unknown++;
            } elseif ($available_version && version_compare($installed_version, $available_version, '<')) {
                $status = 'outdated';
                $outdated++;
            }

            $items[] = [
                'site_id' => (int)$site['id'],
                'site_name' => $site['name'],
                'site_url' => $site['url'],
                'installed_version' => $installed_version ?: 'Unknown',
                'available_version' => $available_version ?: 'Unknown',
                'status' => $status,
                'last_sync' => $site['last_sync'] ?? null
            ];
        }

        return [
            'available_version' => $available_version ?: 'Unknown',
            'outdated_count' => $outdated,
            'unknown_count' => $unknown,
            'sites' => $items
        ];
    }

    private function connector_available_version() {
        $file = dirname(__DIR__, 2) . '/wordpress-plugin/web-manager-connector/web-manager-connector.php';
        if (!is_readable($file)) {
            return null;
        }

        $contents = file_get_contents($file, false, null, 0, 8192);
        if ($contents === false) {
            return null;
        }

        if (preg_match('/^\s*\*\s*Version:\s*(.+)$/mi', $contents, $matches)) {
            return trim($matches[1]);
        }

        return null;
    }
}
