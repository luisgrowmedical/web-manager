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
        $has_pending_updates_data = $this->site_metrics_has_column('pending_updates_data');
        $has_connector_version = $this->site_metrics_has_column('connector_version');
        $has_multisite_data = $this->site_metrics_has_column('is_multisite');
        $pending_updates_select = $has_pending_updates_data ? ', m.pending_updates_data' : ', NULL AS pending_updates_data';
        $pending_updates_inner_select = $has_pending_updates_data ? ', m1.pending_updates_data' : '';
        $connector_version_select = $has_connector_version ? ', m.connector_version' : ', NULL AS connector_version';
        $connector_version_inner_select = $has_connector_version ? ', m1.connector_version' : '';
        $multisite_select = $has_multisite_data ? ', m.is_multisite, m.multisite_network_name, m.multisite_site_count' : ', 0 AS is_multisite, NULL AS multisite_network_name, 0 AS multisite_site_count';
        $multisite_inner_select = $has_multisite_data ? ', m1.is_multisite, m1.multisite_network_name, m1.multisite_site_count' : '';

        // Get sites the user has access to
        if ($is_admin) {
            $stmt = $this->db->query("
                SELECT s.*, m.updates_count, m.plugin_updates_count, m.theme_updates_count {$pending_updates_select} {$connector_version_select} {$multisite_select}, l.sync_status
                FROM sites s
                LEFT JOIN (
                    SELECT m1.site_id, m1.updates_count, m1.plugin_updates_count, m1.theme_updates_count {$pending_updates_inner_select} {$connector_version_inner_select} {$multisite_inner_select}
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
                SELECT s.*, m.updates_count, m.plugin_updates_count, m.theme_updates_count {$pending_updates_select} {$connector_version_select} {$multisite_select}, l.sync_status
                FROM sites s
                JOIN site_user_access sua ON s.id = sua.site_id
                LEFT JOIN (
                    SELECT m1.site_id, m1.updates_count, m1.plugin_updates_count, m1.theme_updates_count {$pending_updates_inner_select} {$connector_version_inner_select} {$multisite_inner_select}
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
            'available_updates' => $this->build_available_updates($sites),
            'multisite_networks' => $this->build_multisite_networks($sites),
            'connector_update' => $this->build_connector_update_status($sites),
            'dashboard_meta' => [
                'stale_sync_days' => 7,
                'core_updates_available' => false,
                'has_pending_updates_data' => $has_pending_updates_data,
                'has_connector_version' => $has_connector_version,
                'has_multisite_data' => $has_multisite_data,
                'safe_update_ready' => false
            ]
        ]);
    }

    private function site_metrics_has_column($column) {
        static $columns = null;

        if ($columns === null) {
            $stmt = $this->db->query("SHOW COLUMNS FROM site_metrics");
            $columns = array_column($stmt->fetchAll(), 'Field');
        }

        return in_array($column, $columns, true);
    }

    private function build_available_updates($sites) {
        $updates = [
            'plugins' => [],
            'themes' => [],
            'wordpress' => []
        ];

        foreach ($sites as $site) {
            if (empty($site['pending_updates_data'])) {
                continue;
            }

            $pending = json_decode($site['pending_updates_data'], true);
            if (!is_array($pending)) {
                continue;
            }

            foreach (($pending['plugins'] ?? []) as $plugin) {
                if (!is_array($plugin)) {
                    continue;
                }

                $key = $plugin['slug'] ?? $plugin['file'] ?? $plugin['name'] ?? null;
                if (!$key) {
                    continue;
                }

                $item_key = 'plugin:' . $key;
                if (!isset($updates['plugins'][$item_key])) {
                    $updates['plugins'][$item_key] = [
                        'id' => md5($item_key),
                        'name' => $plugin['name'] ?? $key,
                        'icon' => $plugin['icon'] ?? null,
                        'current_versions' => [],
                        'available_versions' => [],
                        'site_ids' => [],
                        'sites' => []
                    ];
                }

                $this->add_update_site($updates['plugins'][$item_key], $site, $plugin['current_version'] ?? null, $plugin['new_version'] ?? null);
            }

            foreach (($pending['themes'] ?? []) as $theme) {
                if (!is_array($theme)) {
                    continue;
                }

                $key = $theme['slug'] ?? $theme['name'] ?? null;
                if (!$key) {
                    continue;
                }

                $item_key = 'theme:' . $key;
                if (!isset($updates['themes'][$item_key])) {
                    $updates['themes'][$item_key] = [
                        'id' => md5($item_key),
                        'name' => $theme['name'] ?? $key,
                        'icon' => $theme['icon'] ?? null,
                        'current_versions' => [],
                        'available_versions' => [],
                        'site_ids' => [],
                        'sites' => []
                    ];
                }

                $this->add_update_site($updates['themes'][$item_key], $site, $theme['current_version'] ?? null, $theme['new_version'] ?? null);
            }

            $core = $pending['wordpress'] ?? $pending['core'] ?? null;
            if (is_array($core) && !empty($core['update_available'])) {
                $site_id = (int)$site['id'];
                $updates['wordpress']['site:' . $site_id] = [
                    'id' => md5('wordpress:' . $site_id),
                    'name' => $site['name'],
                    'icon' => $this->site_icon_url($site),
                    'current_version' => $core['current_version'] ?? 'Unknown',
                    'new_version' => $core['new_version'] ?? 'Unknown',
                    'site_count' => 1,
                    'sites' => [$site['name']]
                ];
            }
        }

        foreach (['plugins', 'themes'] as $type) {
            foreach ($updates[$type] as &$item) {
                $item['current_version'] = $this->format_versions($item['current_versions']);
                $item['new_version'] = $this->format_versions($item['available_versions']);
                $item['site_count'] = count($item['site_ids']);
                unset($item['current_versions'], $item['available_versions'], $item['site_ids']);
            }
            unset($item);
            $updates[$type] = array_values($updates[$type]);
        }

        $updates['wordpress'] = array_values($updates['wordpress']);

        return $updates;
    }

    private function add_update_site(&$item, $site, $current_version, $new_version) {
        $site_id = (int)$site['id'];
        $item['site_ids'][$site_id] = true;
        $item['sites'][$site_id] = $site['name'];

        if ($current_version) {
            $item['current_versions'][(string)$current_version] = true;
        }
        if ($new_version) {
            $item['available_versions'][(string)$new_version] = true;
        }
    }

    private function format_versions($versions) {
        $values = array_keys($versions);
        if (empty($values)) {
            return 'Unknown';
        }

        return implode(', ', array_slice($values, 0, 3)) . (count($values) > 3 ? ' +' . (count($values) - 3) : '');
    }

    private function site_icon_url($site) {
        $domain = parse_url($site['url'] ?? '', PHP_URL_HOST);
        if (!$domain) {
            return null;
        }

        return 'https://www.google.com/s2/favicons?domain=' . rawurlencode($domain) . '&sz=64';
    }

    private function build_multisite_networks($sites) {
        $networks = [];

        foreach ($sites as $site) {
            if (empty($site['is_multisite'])) {
                continue;
            }

            $network_name = trim((string)($site['multisite_network_name'] ?? ''));
            if ($network_name === '') {
                $network_name = $site['name'] ?? 'WordPress Network';
            }

            $key = strtolower($network_name);
            if (!isset($networks[$key])) {
                $networks[$key] = [
                    'name' => $network_name,
                    'site_count' => (int)($site['multisite_site_count'] ?? 0),
                    'connected_sites' => 0,
                    'status' => 'Connected',
                    'status_class' => 'badge-success'
                ];
            }

            $networks[$key]['connected_sites']++;
            $networks[$key]['site_count'] = max($networks[$key]['site_count'], (int)($site['multisite_site_count'] ?? 0));

            if (($site['sync_status'] ?? null) === 'error') {
                $networks[$key]['status'] = 'Sync failed';
                $networks[$key]['status_class'] = 'badge-danger';
            } elseif (empty($site['last_sync']) && $networks[$key]['status_class'] !== 'badge-danger') {
                $networks[$key]['status'] = 'Not connected';
                $networks[$key]['status_class'] = 'badge-warning';
            }
        }

        return array_values($networks);
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
