<?php
/**
 * Admin Panel Class
 */

if (!defined('ABSPATH')) exit;

class Web_Manager_Admin_Panel {

    private $option_name = 'web_manager_api_key';

    public function __construct() {
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_init', [$this, 'handle_actions']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function add_menu() {
        add_menu_page(
            'web-manager',
            'web-manager',
            'manage_options',
            'web-manager-connector',
            [$this, 'render_page'],
            'dashicons-chart-line',
            100
        );
    }

    public function enqueue_assets($hook) {
        if ($hook !== 'toplevel_page_web-manager-connector') return;

        wp_enqueue_style('web-manager-admin', WEB_MANAGER_URL . 'assets/css/admin-style.css', [], WEB_MANAGER_VERSION);
        wp_enqueue_script('web-manager-admin', WEB_MANAGER_URL . 'assets/js/admin-script.js', ['jquery'], WEB_MANAGER_VERSION, true);
    }

    public function handle_actions() {
        if (isset($_POST['web_manager_regenerate']) && check_admin_referer('web_manager_action')) {
            update_option($this->option_name, bin2hex(random_bytes(32)));
            add_settings_error('web_manager', 'regenerated', 'API Key has been regenerated.', 'updated');
        }

        // Default key
        if (!get_option($this->option_name)) {
            update_option($this->option_name, bin2hex(random_bytes(32)));
        }
    }

    public function render_page() {
        $api_key = get_option($this->option_name);
        $site_url = home_url();
        $endpoint = home_url('/wp-json/web-manager/v1/stats');
        ?>
        <div class="wrap web-manager-wrap">
            <div class="web-manager-header">
                <div class="web-manager-logo">
                    <span class="dashicons dashicons-chart-line"></span>
                    <h1>web-manager <span>Connector</span></h1>
                </div>
                <div class="web-manager-status">
                    <span class="status-dot online"></span> Active & Connected
                </div>
            </div>

            <?php settings_errors('web_manager'); ?>

            <div class="web-manager-container">
                <div class="web-manager-main-card">
                    <div class="card-header">
                        <h2>Connection Details</h2>
                        <p>Copy these credentials to your web-manager dashboard to start auditing this site.</p>
                    </div>

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
                        <p class="field-desc">This endpoint uses a high-priority bypass and is authorized to work even if the "Disable REST API" plugin is active.</p>
                    </div>

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
}
