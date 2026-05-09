<?php
/**
 * Plugin updater integration.
 */

if (!defined('ABSPATH')) exit;

if (!class_exists('Web_Manager_Plugin_Updater', false)) {
class Web_Manager_Plugin_Updater {

    private $option_key = 'web_manager_api_key';
    private $platform_option = 'web_manager_platform_url';
    private $plugin_file;
    private $plugin_basename;
    private $slug;
    private $version;

    public function __construct($plugin_file, $version) {
        $this->plugin_file = $plugin_file;
        $this->plugin_basename = plugin_basename($plugin_file);
        $this->slug = dirname($this->plugin_basename);
        $this->version = $version;

        add_filter('pre_set_site_transient_update_plugins', [$this, 'inject_update']);
        add_filter('plugins_api', [$this, 'plugin_information'], 20, 3);
    }

    public function inject_update($transient) {
        if (!is_object($transient)) {
            $transient = new stdClass();
        }

        $manifest = $this->get_manifest();
        if (!$manifest || empty($manifest['version']) || version_compare($manifest['version'], $this->version, '<=')) {
            return $transient;
        }

        if (!isset($transient->response) || !is_array($transient->response)) {
            $transient->response = [];
        }

        $transient->response[$this->plugin_basename] = (object)[
            'id' => $manifest['slug'] ?? $this->slug,
            'slug' => $manifest['slug'] ?? $this->slug,
            'plugin' => $manifest['plugin'] ?? $this->plugin_basename,
            'new_version' => $manifest['version'],
            'url' => $manifest['url'] ?? '',
            'package' => $manifest['package'] ?? '',
            'tested' => $manifest['tested'] ?? '',
            'requires' => $manifest['requires'] ?? '',
            'requires_php' => $manifest['requires_php'] ?? '',
        ];

        return $transient;
    }

    public function plugin_information($result, $action, $args) {
        if ($action !== 'plugin_information' || empty($args->slug) || $args->slug !== $this->slug) {
            return $result;
        }

        $manifest = $this->get_manifest();
        if (!$manifest) {
            return $result;
        }

        return (object)[
            'name' => $manifest['name'] ?? 'web-manager Connector',
            'slug' => $manifest['slug'] ?? $this->slug,
            'version' => $manifest['version'] ?? $this->version,
            'author' => '<a href="' . esc_url($manifest['url'] ?? '') . '">web-manager</a>',
            'homepage' => $manifest['url'] ?? '',
            'requires' => $manifest['requires'] ?? '',
            'requires_php' => $manifest['requires_php'] ?? '',
            'tested' => $manifest['tested'] ?? '',
            'download_link' => $manifest['package'] ?? '',
            'sections' => $manifest['sections'] ?? [
                'description' => 'web-manager Connector',
                'changelog' => 'No changelog available.'
            ],
        ];
    }

    private function get_manifest() {
        $endpoint = $this->manifest_url();
        if (!$endpoint) {
            return null;
        }

        $cache_key = 'web_manager_connector_update_manifest';
        $cached = get_site_transient($cache_key);
        if (is_array($cached)) {
            return $cached;
        }

        $api_key = get_option($this->option_key);
        $response = wp_remote_get($endpoint, [
            'timeout' => 10,
            'headers' => [
                'Accept' => 'application/json',
                'X-API-KEY' => $api_key,
            ],
            'sslverify' => apply_filters('web_manager_connector_sslverify', true),
        ]);

        if (is_wp_error($response)) {
            $this->log('Update manifest request failed: ' . $response->get_error_message());
            return null;
        }

        $code = (int) wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        $manifest = json_decode($body, true);

        if ($code !== 200 || !is_array($manifest) || empty($manifest['slug'])) {
            $this->log('Update manifest returned an invalid response. HTTP ' . $code);
            return null;
        }

        set_site_transient($cache_key, $manifest, 6 * HOUR_IN_SECONDS);
        return $manifest;
    }

    private function manifest_url() {
        $platform_url = trim((string)get_option($this->platform_option));
        if ($platform_url === '' && defined('WEB_MANAGER_CONNECTOR_PLATFORM_URL')) {
            $platform_url = WEB_MANAGER_CONNECTOR_PLATFORM_URL;
        }

        $platform_url = untrailingslashit($platform_url);
        if ($platform_url === '') {
            return null;
        }

        $api_key = get_option($this->option_key);
        if (empty($api_key)) {
            return null;
        }

        return add_query_arg([
            'route' => 'connector-update',
            'action' => 'manifest',
            'site_url' => home_url(),
            'api_key' => $api_key,
            'installed_version' => $this->version,
            'plugin_file' => $this->plugin_basename,
        ], $platform_url . '/public/index.php');
    }

    private function log($message) {
        if (function_exists('web_manager_connector_log')) {
            web_manager_connector_log($message);
        }
    }
}
}
