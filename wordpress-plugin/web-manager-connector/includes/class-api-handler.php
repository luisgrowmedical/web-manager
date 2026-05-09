<?php
/**
 * API Handler Class
 */

if (!defined('ABSPATH')) exit;

if (!class_exists('Web_Manager_API_Handler', false)) {
class Web_Manager_API_Handler {

    private $option_name = 'web_manager_api_key';

    public function __construct() {
        add_action('init', [$this, 'ensure_api_key']);

        // Register native REST API routes
        add_action('rest_api_init', [$this, 'register_api_routes']);
        
        // Whitelist our namespace for common REST API blockers
        add_filter('rest_authentication_errors', [$this, 'whitelist_rest_api'], 99);
    }

    /**
     * Register the native WordPress REST API routes
     * This allows the 'web-manager/v1' namespace to appear in 
     * plugins like "Disable REST API".
     */
    public function register_api_routes() {
        if (!function_exists('register_rest_route')) {
            $this->log('register_rest_route is unavailable.');
            return;
        }

        register_rest_route('web-manager/v1', '/stats', [
            'methods'  => 'GET',
            'callback' => [$this, 'get_stats'],
            'permission_callback' => [$this, 'validate_api_key'],
        ]);
    }

    public function ensure_api_key() {
        if (get_option($this->option_name)) {
            return;
        }

        if (function_exists('wp_generate_password')) {
            update_option($this->option_name, wp_generate_password(64, false, false), false);
            return;
        }

        update_option($this->option_name, hash('sha256', uniqid('', true)), false);
    }

    /**
     * Validate the API Key for the REST request
     */
    public function validate_api_key($request) {
        $provided_key = $request->get_header('X-API-KEY') ?: $request->get_param('api_key');
        $stored_key = get_option($this->option_name);

        if (empty($stored_key)) {
            $this->log('REST request rejected because the API key has not been generated.');
            return new WP_Error('web_manager_missing_key', 'The web-manager API Key has not been generated yet.', ['status' => 500]);
        }

        if (empty($provided_key) || !hash_equals((string)$stored_key, (string)$provided_key)) {
            $this->log('REST request rejected because the API key is invalid or missing.');
            return new WP_Error('unauthorized', 'Invalid or missing API Key', ['status' => 401]);
        }

        return true;
    }

    /**
     * Callback for the REST API endpoint
     */
    public function get_stats() {
        return $this->collect_site_data();
    }

    /**
     * If the web-manager app tries to use the standard REST API, 
     * we try to whitelist it if the API key is present.
     */
    public function whitelist_rest_api($result) {
        // If it's our namespace, we handle the authentication ourselves or force bypass
        if (isset($_SERVER['REQUEST_URI']) && strpos(sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])), 'web-manager/v1') !== false) {
            return true; 
        }
        return $result;
    }


    private function collect_site_data() {
        // Pages & Posts
        $pages = wp_count_posts('page');
        $posts = wp_count_posts('post');
        
        // Drafts
        $drafts = (int)$posts->draft + (int)$pages->draft;

        // Themes
        $themes = wp_get_themes();
        $active_theme = wp_get_theme();
        
        // Plugins
        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $all_plugins = get_plugins();
        $active_plugins = get_option('active_plugins');

        // Images
        $images = wp_count_posts('attachment');

        $updates = $this->get_pending_updates_count();

        return [
            'site_name' => get_bloginfo('name'),
            'site_url'  => home_url(),
            'multisite' => $this->get_multisite_data(),
            'pages'     => (int)$pages->publish,
            'posts'     => (int)$posts->publish,
            'drafts'    => (int)$drafts,
            'themes'    => [
                'active' => $active_theme->get('Name'),
                'total'  => count($themes)
            ],
            'plugins'   => [
                'active' => is_array($active_plugins) ? count($active_plugins) : 0,
                'total'  => count($all_plugins)
            ],
            'connector' => [
                'slug' => 'web-manager-connector',
                'file' => defined('WEB_MANAGER_CONNECTOR_FILE') ? plugin_basename(WEB_MANAGER_CONNECTOR_FILE) : 'web-manager-connector/web-manager-connector.php',
                'version' => defined('WEB_MANAGER_CONNECTOR_VERSION') ? WEB_MANAGER_CONNECTOR_VERSION : '',
                'platform_url' => get_option('web_manager_platform_url', defined('WEB_MANAGER_CONNECTOR_PLATFORM_URL') ? WEB_MANAGER_CONNECTOR_PLATFORM_URL : '')
            ],
            'weight'    => $this->get_site_weight(),
            'images'    => (int)$images->inherit,
            'updates'   => $updates
        ];
    }

    private function get_multisite_data() {
        $is_multisite = is_multisite();
        $network_name = get_bloginfo('name');
        $site_count = 1;

        if ($is_multisite) {
            $network = function_exists('get_network') ? get_network() : null;
            if ($network && !empty($network->site_name)) {
                $network_name = $network->site_name;
            }

            if (function_exists('get_sites')) {
                $site_count = count(get_sites([
                    'fields' => 'ids',
                    'number' => 0
                ]));
            }
        }

        return [
            'is_multisite' => $is_multisite,
            'network_name' => $network_name,
            'site_count' => (int)$site_count
        ];
    }

    private function get_pending_updates_count() {
        if (!function_exists('wp_version_check') || !function_exists('wp_update_plugins') || !function_exists('wp_update_themes')) {
            require_once ABSPATH . 'wp-includes/update.php';
        }
        if (!function_exists('get_core_updates') && file_exists(ABSPATH . 'wp-admin/includes/update.php')) {
            require_once ABSPATH . 'wp-admin/includes/update.php';
        }

        wp_version_check();
        wp_update_plugins();
        wp_update_themes();

        $plugin_updates_count = 0;
        $theme_updates_count = 0;
        $core_updates_count = 0;

        $plugin_updates = get_site_transient('update_plugins');
        $plugin_update_items = [];
        if (isset($plugin_updates->response) && is_array($plugin_updates->response)) {
            $plugin_updates_count = count($plugin_updates->response);
            if (!function_exists('get_plugins')) {
                require_once ABSPATH . 'wp-admin/includes/plugin.php';
            }
            $installed_plugins = get_plugins();

            foreach ($plugin_updates->response as $plugin_file => $plugin_update) {
                $plugin_data = isset($installed_plugins[$plugin_file]) ? $installed_plugins[$plugin_file] : [];
                $plugin_update_items[] = [
                    'file' => $plugin_file,
                    'slug' => isset($plugin_update->slug) ? $plugin_update->slug : dirname($plugin_file),
                    'name' => $plugin_data['Name'] ?? (isset($plugin_update->slug) ? $plugin_update->slug : $plugin_file),
                    'current_version' => $plugin_data['Version'] ?? '',
                    'new_version' => $plugin_update->new_version ?? '',
                    'icon' => $this->pick_update_icon($plugin_update->icons ?? null)
                ];
            }
        }

        $theme_updates = get_site_transient('update_themes');
        $theme_update_items = [];
        if (isset($theme_updates->response) && is_array($theme_updates->response)) {
            $theme_updates_count = count($theme_updates->response);
            $installed_themes = wp_get_themes();

            foreach ($theme_updates->response as $stylesheet => $theme_update) {
                $theme = $installed_themes[$stylesheet] ?? null;
                $theme_update_items[] = [
                    'slug' => $stylesheet,
                    'name' => $theme ? $theme->get('Name') : $stylesheet,
                    'current_version' => $theme ? $theme->get('Version') : '',
                    'new_version' => is_array($theme_update) ? ($theme_update['new_version'] ?? '') : ($theme_update->new_version ?? ''),
                    'icon' => $theme && method_exists($theme, 'get_screenshot') ? $theme->get_screenshot() : null
                ];
            }
        }

        $core_update_item = [
            'update_available' => false,
            'current_version' => $GLOBALS['wp_version'] ?? get_bloginfo('version'),
            'new_version' => ''
        ];

        if (function_exists('get_core_updates')) {
            $core_updates = get_core_updates();
            if (is_array($core_updates)) {
                foreach ($core_updates as $core_update) {
                    if (isset($core_update->response) && $core_update->response === 'upgrade') {
                        $core_updates_count = 1;
                        $core_update_item = [
                            'update_available' => true,
                            'current_version' => $GLOBALS['wp_version'] ?? get_bloginfo('version'),
                            'new_version' => $core_update->version ?? ''
                        ];
                        break;
                    }
                }
            }
        }

        return [
            'total' => $plugin_updates_count + $theme_updates_count + $core_updates_count,
            'plugins' => $plugin_updates_count,
            'themes' => $theme_updates_count,
            'core' => $core_updates_count,
            'details' => [
                'plugins' => $plugin_update_items,
                'themes' => $theme_update_items,
                'wordpress' => $core_update_item
            ]
        ];
    }

    private function pick_update_icon($icons) {
        if (empty($icons)) {
            return null;
        }

        if (is_object($icons)) {
            $icons = (array)$icons;
        }

        if (!is_array($icons)) {
            return null;
        }

        foreach (['svg', '2x', '1x', 'default'] as $key) {
            if (!empty($icons[$key])) {
                return $icons[$key];
            }
        }

        $first = reset($icons);
        return is_string($first) ? $first : null;
    }

    private function get_site_weight() {
        $upload_dir = wp_upload_dir();
        $base_dir = $upload_dir['basedir'];
        
        $total_size = 0;
        $total_size += $this->get_dir_size($base_dir);
        $total_size += $this->get_dir_size(WP_CONTENT_DIR . '/plugins');
        $total_size += $this->get_dir_size(WP_CONTENT_DIR . '/themes');

        return $this->format_size($total_size);
    }

    private function get_dir_size($directory) {
        $size = 0;
        if (!is_dir($directory)) return 0;
        try {
            foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS)) as $file) {
                $size += $file->getSize();
            }
        } catch (Exception $e) {
            $this->log('Directory size scan failed for ' . $directory . ': ' . $e->getMessage());
        }
        return $size;
    }

    private function format_size($bytes) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        for ($i = 0; $bytes > 1024; $i++) $bytes /= 1024;
        return round($bytes, 2) . ' ' . $units[$i];
    }

    private function log($message) {
        if (function_exists('web_manager_connector_log')) {
            web_manager_connector_log($message);
        }
    }
}
}
