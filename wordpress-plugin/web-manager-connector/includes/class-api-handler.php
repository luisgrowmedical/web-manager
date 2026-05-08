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
            'weight'    => $this->get_site_weight(),
            'images'    => (int)$images->inherit,
            'updates'   => $updates
        ];
    }

    private function get_pending_updates_count() {
        if (!function_exists('wp_version_check') || !function_exists('wp_update_plugins') || !function_exists('wp_update_themes')) {
            require_once ABSPATH . 'wp-includes/update.php';
        }

        wp_version_check();
        wp_update_plugins();
        wp_update_themes();

        $plugin_updates_count = 0;
        $theme_updates_count = 0;
        $core_updates_count = 0;

        $plugin_updates = get_site_transient('update_plugins');
        if (isset($plugin_updates->response) && is_array($plugin_updates->response)) {
            $plugin_updates_count = count($plugin_updates->response);
        }

        $theme_updates = get_site_transient('update_themes');
        if (isset($theme_updates->response) && is_array($theme_updates->response)) {
            $theme_updates_count = count($theme_updates->response);
        }

        if (function_exists('get_core_updates')) {
            $core_updates = get_core_updates();
            if (is_array($core_updates)) {
                foreach ($core_updates as $core_update) {
                    if (isset($core_update->response) && $core_update->response === 'upgrade') {
                        $core_updates_count = 1;
                        break;
                    }
                }
            }
        }

        return [
            'total' => $plugin_updates_count + $theme_updates_count + $core_updates_count,
            'plugins' => $plugin_updates_count,
            'themes' => $theme_updates_count,
            'core' => $core_updates_count
        ];
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
