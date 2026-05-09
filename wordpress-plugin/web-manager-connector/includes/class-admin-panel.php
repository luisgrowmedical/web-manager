<?php
/**
 * Admin Panel Class
 */

if (!defined('ABSPATH')) exit;

if (!class_exists('Web_Manager_Admin_Panel', false)) {
class Web_Manager_Admin_Panel {

    private $option_name = 'web_manager_api_key';
    private $platform_option = 'web_manager_platform_url';
    private $plugin_url;
    private $version;

    public function __construct($plugin_url, $version) {
        $this->plugin_url = $plugin_url;
        $this->version = $version;

        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_init', [$this, 'handle_actions']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function add_menu() {
        $hook = add_menu_page(
            'web-manager',
            'web-manager',
            'manage_options',
            'web-manager',
            [$this, 'render_page'],
            'dashicons-chart-line',
            100
        );

        if (!$hook) {
            $this->log('Admin menu registration failed.');
        }
    }

    public function enqueue_assets($hook) {
        if ($hook !== 'toplevel_page_web-manager') return;

        $css_file = plugin_dir_path(WEB_MANAGER_CONNECTOR_FILE) . 'assets/css/admin-style.css';
        $js_file = plugin_dir_path(WEB_MANAGER_CONNECTOR_FILE) . 'assets/js/admin-script.js';

        if (is_readable($css_file)) {
            wp_enqueue_style('web-manager-admin', $this->plugin_url . 'assets/css/admin-style.css', [], $this->version);
        } else {
            $this->log('Admin CSS asset is missing.');
        }

        if (is_readable($js_file)) {
            wp_enqueue_script('web-manager-admin', $this->plugin_url . 'assets/js/admin-script.js', ['jquery'], $this->version, true);
        } else {
            $this->log('Admin JS asset is missing.');
        }
    }

    public function handle_actions() {
        if (!current_user_can('manage_options')) {
            return;
        }

        if (isset($_POST['web_manager_regenerate']) && check_admin_referer('web_manager_action')) {
            update_option($this->option_name, $this->generate_api_key(), false);
            delete_site_transient('web_manager_connector_update_manifest');
            add_settings_error('web_manager', 'regenerated', 'API Key has been regenerated.', 'updated');
        }

        if (isset($_POST['web_manager_save_settings']) && check_admin_referer('web_manager_action')) {
            $platform_url = isset($_POST['web_manager_platform_url']) ? esc_url_raw(wp_unslash($_POST['web_manager_platform_url'])) : '';
            update_option($this->platform_option, untrailingslashit($platform_url), false);
            delete_site_transient('web_manager_connector_update_manifest');
            add_settings_error('web_manager', 'settings_saved', 'Connector settings saved.', 'updated');
        }

        // Default key
        if (!get_option($this->option_name)) {
            update_option($this->option_name, $this->generate_api_key(), false);
        }

        if (!get_option($this->platform_option) && defined('WEB_MANAGER_CONNECTOR_PLATFORM_URL')) {
            update_option($this->platform_option, untrailingslashit(WEB_MANAGER_CONNECTOR_PLATFORM_URL), false);
        }
    }

    private function generate_api_key() {
        if (function_exists('wp_generate_password')) {
            return wp_generate_password(64, false, false);
        }

        if (function_exists('wp_rand')) {
            return hash('sha256', uniqid((string)wp_rand(), true));
        }

        return hash('sha256', uniqid('', true));
    }

    public function render_page() {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have permission to access this page.', 'web-manager'));
        }

        $api_key = get_option($this->option_name);
        $platform_url = get_option($this->platform_option, defined('WEB_MANAGER_CONNECTOR_PLATFORM_URL') ? WEB_MANAGER_CONNECTOR_PLATFORM_URL : '');
        $site_url = home_url();
        $endpoint = home_url('/wp-json/web-manager/v1/stats');
        $diagnostics = $this->get_diagnostics($endpoint, $api_key);
        ?>
        <div class="wrap web-manager-wrap">
            <div class="web-manager-header">
                <div class="web-manager-logo">
                    <span class="dashicons dashicons-chart-line"></span>
                    <h1>web-manager <span>Connector</span></h1>
                </div>
                <div class="web-manager-status">
                    <span class="status-dot <?php echo $diagnostics['ok'] ? 'online' : 'warning'; ?>"></span>
                    <?php echo esc_html($diagnostics['label']); ?>
                </div>
            </div>

            <?php settings_errors('web_manager'); ?>

            <div class="web-manager-container">
                <div class="web-manager-main-card">
                    <div class="card-header">
                        <h2>Connection Details</h2>
                        <p>Copy these credentials to your web-manager dashboard to start auditing this site.</p>
                    </div>

                    <?php if (!$diagnostics['ok']): ?>
                        <div class="web-manager-alert error">
                            <strong>Connection check failed.</strong>
                            <span><?php echo esc_html($diagnostics['message']); ?></span>
                        </div>
                    <?php else: ?>
                        <div class="web-manager-alert success">
                            <strong>Connection check passed.</strong>
                            <span><?php echo esc_html($diagnostics['message']); ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="web-manager-field-group">
                        <label>Site URL</label>
                        <div class="copy-input">
                            <input type="text" readonly value="<?php echo esc_url($site_url); ?>" id="web-manager-url">
                            <button type="button" class="web-manager-copy-btn" data-target="web-manager-url">Copy</button>
                        </div>
                    </div>

                    <div class="web-manager-field-group">
                        <label>API Key</label>
                        <div class="copy-input">
                            <input type="password" readonly value="<?php echo esc_attr($api_key); ?>" id="web-manager-key">
                            <button type="button" class="web-manager-view-btn">Show</button>
                            <button type="button" class="web-manager-copy-btn" data-target="web-manager-key">Copy</button>
                        </div>
                    </div>

                    <div class="web-manager-field-group">
                        <label>Custom Endpoint</label>
                        <div class="copy-input">
                            <input type="text" readonly value="<?php echo esc_url($endpoint); ?>" id="web-manager-endpoint">
                            <button type="button" class="web-manager-copy-btn" data-target="web-manager-endpoint">Copy</button>
                        </div>
                        <p class="field-desc">This endpoint is authorized with the API Key. If a REST-blocking plugin is active, allow the web-manager/v1 namespace.</p>
                    </div>

                    <form method="post" action="" class="web-manager-field-group">
                        <?php wp_nonce_field('web_manager_action'); ?>
                        <input type="hidden" name="web_manager_save_settings" value="1">
                        <label>web-manager Platform URL</label>
                        <div class="copy-input">
                            <input type="url" name="web_manager_platform_url" value="<?php echo esc_attr($platform_url); ?>" id="web-manager-platform-url" placeholder="https://example.com/web-manager">
                            <button type="submit" class="web-manager-copy-btn">Save</button>
                        </div>
                        <p class="field-desc">The connector uses this URL to check for plugin updates from the central app.</p>
                    </form>

                    <div class="web-manager-actions">
                        <form method="post" action="">
                            <?php wp_nonce_field('web_manager_action'); ?>
                            <input type="hidden" name="web_manager_regenerate" value="1">
                            <button type="submit" class="web-manager-btn-secondary" onclick="return confirm('Regenerating the key will break existing connections. Continue?')">
                                Regenerate API Key
                            </button>
                        </form>
                    </div>
                </div>

                <div class="web-manager-sidebar-info">
                    <div class="info-card">
                        <h3>Quick Setup</h3>
                        <ol>
                            <li>Log in to your <a href="#" target="_blank">web-manager Panel</a>.</li>
                            <li>Go to <strong>Sites > Add New</strong>.</li>
                            <li>Paste the <strong>URL</strong> and <strong>API Key</strong> from this page.</li>
                            <li>Save and wait for the first sync!</li>
                        </ol>
                    </div>
                    
                    <div class="info-card help">
                        <h3>Need Help?</h3>
                        <p>Check our documentation or contact support if you have issues connecting your site.</p>
                        <a href="#" class="web-manager-btn-link">View Documentation</a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    private function get_diagnostics($endpoint, $api_key) {
        if (empty($api_key)) {
            return [
                'ok' => false,
                'label' => 'Setup needed',
                'message' => 'The API Key has not been generated yet. Reload this page or regenerate the key.',
            ];
        }

        if (!function_exists('wp_remote_get')) {
            return [
                'ok' => false,
                'label' => 'HTTP unavailable',
                'message' => 'WordPress HTTP functions are unavailable, so the connector cannot test the endpoint.',
            ];
        }

        $response = wp_remote_get(add_query_arg('api_key', $api_key, $endpoint), [
            'timeout' => 10,
            'headers' => [
                'X-API-KEY' => $api_key,
            ],
            'sslverify' => apply_filters('web_manager_connector_sslverify', true),
        ]);

        if (is_wp_error($response)) {
            $message = $response->get_error_message();
            $this->log('Self-check HTTP error: ' . $message);

            return [
                'ok' => false,
                'label' => 'Connection error',
                'message' => $message,
            ];
        }

        $code = (int) wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if ($code === 200 && is_array($data) && isset($data['site_url'])) {
            return [
                'ok' => true,
                'label' => 'Active & Connected',
                'message' => 'The local REST endpoint responded correctly.',
            ];
        }

        $message = 'Unexpected endpoint response.';
        if ($code > 0) {
            $message .= ' HTTP ' . $code . '.';
        }
        if (is_array($data) && isset($data['message'])) {
            $message .= ' ' . $data['message'];
        } elseif (!empty($body)) {
            $message .= ' ' . wp_trim_words(wp_strip_all_tags($body), 20);
        }

        $this->log('Self-check failed: ' . $message);

        return [
            'ok' => false,
            'label' => 'Check failed',
            'message' => $message,
        ];
    }

    private function log($message) {
        if (function_exists('web_manager_connector_log')) {
            web_manager_connector_log($message);
        }
    }
}
}
