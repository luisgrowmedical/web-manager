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
            $stmt = $this->db->query("
                SELECT s.*, m.updates_count, m.plugin_updates_count, m.theme_updates_count, l.sync_status
                FROM sites s
                LEFT JOIN (
                    SELECT m1.site_id, m1.updates_count, m1.plugin_updates_count, m1.theme_updates_count
                    FROM site_metrics m1
                    INNER JOIN (
                        SELECT site_id, MAX(id) as metric_id
                        FROM site_metrics 
                        GROUP BY site_id
                    ) m2 ON m1.id = m2.metric_id
                ) m ON s.id = m.site_id
                LEFT JOIN (
                    SELECT l1.site_id, l1.status as sync_status
                    FROM api_logs l1
                    INNER JOIN (
                        SELECT site_id, MAX(id) as log_id
                        FROM api_logs
                        WHERE action = 'sync'
                        GROUP BY site_id
                    ) l2 ON l1.id = l2.log_id
                ) l ON s.id = l.site_id
                ORDER BY s.last_sync IS NULL ASC, s.last_sync DESC
            ");
        } else {
            $stmt = $this->db->prepare("
                SELECT s.*, m.updates_count, m.plugin_updates_count, m.theme_updates_count, l.sync_status
                FROM sites s
                JOIN site_user_access sua ON s.id = sua.site_id
                LEFT JOIN (
                    SELECT m1.site_id, m1.updates_count, m1.plugin_updates_count, m1.theme_updates_count
                    FROM site_metrics m1
                    INNER JOIN (
                        SELECT site_id, MAX(id) as metric_id
                        FROM site_metrics 
                        GROUP BY site_id
                    ) m2 ON m1.id = m2.metric_id
                ) m ON s.id = m.site_id
                LEFT JOIN (
                    SELECT l1.site_id, l1.status as sync_status
                    FROM api_logs l1
                    INNER JOIN (
                        SELECT site_id, MAX(id) as log_id
                        FROM api_logs
                        WHERE action = 'sync'
                        GROUP BY site_id
                    ) l2 ON l1.id = l2.log_id
                ) l ON s.id = l.site_id
                WHERE sua.user_id = ?
                ORDER BY s.last_sync IS NULL ASC, s.last_sync DESC
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
                SELECT SUM(m1.pages_count) as pages,
                       SUM(m1.posts_count) as posts,
                       SUM(m1.images_count) as images,
                       SUM(m1.updates_count) as updates,
                       SUM(m1.plugin_updates_count) as plugin_updates,
                       SUM(m1.theme_updates_count) as theme_updates
                FROM site_metrics m1
                INNER JOIN (
                    SELECT site_id, MAX(id) as metric_id
                    FROM site_metrics
                    WHERE site_id IN ($placeholders)
                    GROUP BY site_id
                ) m2 ON m1.id = m2.metric_id
            ");
            $stmt->execute($site_ids);
            $metrics = $stmt->fetch();
            
            $stats['total_pages'] = $metrics['pages'] ?? 0;
            $stats['total_posts'] = $metrics['posts'] ?? 0;
            $stats['total_images'] = $metrics['images'] ?? 0;
            $stats['total_updates'] = $metrics['updates'] ?? 0;
            $stats['plugin_updates'] = $metrics['plugin_updates'] ?? 0;
            $stats['theme_updates'] = $metrics['theme_updates'] ?? 0;
        }

        $attention_sites = [];
        $recent_activity = [];
        $stale_after = strtotime('-7 days');

        foreach ($sites as $site) {
            $plugin_updates = (int)($site['plugin_updates_count'] ?? 0);
            $theme_updates = (int)($site['theme_updates_count'] ?? 0);
            $updates_count = (int)($site['updates_count'] ?? ($plugin_updates + $theme_updates));
            $last_sync_time = !empty($site['last_sync']) ? strtotime($site['last_sync']) : null;
            $sync_status = $site['sync_status'] ?? null;

            $issues = [];
            if ($sync_status === 'error') {
                $issues[] = 'Sync failed';
            }
            if (!$last_sync_time) {
                $issues[] = 'Never synced';
            } elseif ($last_sync_time < $stale_after) {
                $issues[] = 'Stale sync';
            }
            if ($updates_count > 0 || ($plugin_updates + $theme_updates) > 0) {
                $issues[] = 'Updates pending';
            }

            if ($issues) {
                $attention_sites[] = [
                    'site' => $site,
                    'issues' => $issues
                ];
            }

            if ($sync_status === 'error') {
                $recent_activity[] = [
                    'label' => 'Site disconnected',
                    'site' => $site['name'],
                    'time' => $site['last_sync'] ?? null,
                    'badge' => 'badge-danger'
                ];
            } elseif ($updates_count > 0 || ($plugin_updates + $theme_updates) > 0) {
                $recent_activity[] = [
                    'label' => 'Updates pending',
                    'site' => $site['name'],
                    'time' => $site['last_sync'] ?? null,
                    'badge' => 'badge-warning'
                ];
            } elseif ($last_sync_time) {
                $recent_activity[] = [
                    'label' => 'Site synced',
                    'site' => $site['name'],
                    'time' => $site['last_sync'],
                    'badge' => 'badge-success'
                ];
            } else {
                $recent_activity[] = [
                    'label' => 'Connection created',
                    'site' => $site['name'],
                    'time' => null,
                    'badge' => 'badge-warning'
                ];
            }
        }

        view('dashboard/index', [
            'title' => 'Dashboard',
            'sites' => $sites,
            'stats' => $stats,
            'attention_sites' => $attention_sites,
            'recent_activity' => array_slice($recent_activity, 0, 6),
            'dashboard_meta' => [
                'stale_sync_days' => 7,
                'core_updates_available' => false
            ]
        ]);
    }
}
