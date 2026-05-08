<?php
/**
 * Dashboard Controller
 */

class DashboardController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function index() {
        $user_id = $_SESSION['user_id'];
        $is_admin = $_SESSION['role'] === 'admin';

        // Get sites the user has access to
        if ($is_admin) {
            $stmt = $this->db->query("SELECT * FROM sites ORDER BY created_at DESC");
        } else {
            $stmt = $this->db->prepare("
                SELECT s.* FROM sites s
                JOIN site_user_access sua ON s.id = sua.site_id
                WHERE sua.user_id = ?
                ORDER BY s.created_at DESC
            ");
            $stmt->execute([$user_id]);
        }
        $sites = $stmt->fetchAll();

        // Get some aggregate metrics
        $stats = [
            'total_sites' => count($sites),
            'total_pages' => 0,
            'total_posts' => 0,
            'total_images' => 0
        ];

        if ($sites) {
            $site_ids = array_column($sites, 'id');
            $placeholders = implode(',', array_fill(0, count($site_ids), '?'));
            
            $stmt = $this->db->prepare("
                SELECT SUM(m1.pages_count) as pages, SUM(m1.posts_count) as posts, SUM(m1.images_count) as images, SUM(m1.updates_count) as updates
                FROM site_metrics m1
                INNER JOIN (
                    SELECT site_id, MAX(sync_date) as max_sync
                    FROM site_metrics
                    WHERE site_id IN ($placeholders)
                    GROUP BY site_id
                ) m2 ON m1.site_id = m2.site_id AND m1.sync_date = m2.max_sync
            ");
            $stmt->execute($site_ids);
            $metrics = $stmt->fetch();
            
            $stats['total_pages'] = $metrics['pages'] ?? 0;
            $stats['total_posts'] = $metrics['posts'] ?? 0;
            $stats['total_images'] = $metrics['images'] ?? 0;
            $stats['total_updates'] = $metrics['updates'] ?? 0;
        }

        view('dashboard/index', [
            'title' => 'Dashboard',
            'sites' => $sites,
            'stats' => $stats
        ]);
    }
}
