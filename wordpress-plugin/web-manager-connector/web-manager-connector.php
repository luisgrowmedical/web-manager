<?php
/**
 * Plugin Name: web-manager
 * Plugin URI: https://localhost/web-manager
 * Description: Connects your WordPress site to the web-manager platform.
 * Version: 1.1.6
 * Author: web-manager Team
 * Text Domain: web-manager
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

if (!defined('ABSPATH')) exit;

if (!defined('WEB_MANAGER_CONNECTOR_VERSION')) {
    define('WEB_MANAGER_CONNECTOR_VERSION', '1.1.6');
}
if (!defined('WEB_MANAGER_CONNECTOR_FILE')) {
    define('WEB_MANAGER_CONNECTOR_FILE', __FILE__);
}
if (!defined('WEB_MANAGER_CONNECTOR_PATH')) {
    define('WEB_MANAGER_CONNECTOR_PATH', plugin_dir_path(WEB_MANAGER_CONNECTOR_FILE));
}
if (!defined('WEB_MANAGER_CONNECTOR_URL')) {
    define('WEB_MANAGER_CONNECTOR_URL', plugin_dir_url(WEB_MANAGER_CONNECTOR_FILE));
}
if (!defined('WEB_MANAGER_CONNECTOR_MIN_PHP')) {
    define('WEB_MANAGER_CONNECTOR_MIN_PHP', '7.4');
}

if (!function_exists('web_manager_connector_log')) {
    function web_manager_connector_log($message) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('[web-manager Connector] ' . $message);
        }
    }
}

if (!function_exists('web_manager_connector_dependency_errors')) {
    function web_manager_connector_dependency_errors() {
        $errors = [];

        if (version_compare(PHP_VERSION, WEB_MANAGER_CONNECTOR_MIN_PHP, '<')) {
            $errors[] = sprintf(
                'PHP %s or newer is required. Current PHP version: %s.',
                WEB_MANAGER_CONNECTOR_MIN_PHP,
                PHP_VERSION
            );
        }

        if (!function_exists('register_rest_route')) {
            $errors[] = 'The WordPress REST API is unavailable.';
        }

        foreach ([
            WEB_MANAGER_CONNECTOR_PATH . 'includes/class-api-handler.php',
            WEB_MANAGER_CONNECTOR_PATH . 'includes/class-admin-panel.php',
        ] as $required_file) {
            if (!is_readable($required_file)) {
                $errors[] = 'Missing or unreadable plugin file: ' . str_replace(WEB_MANAGER_CONNECTOR_PATH, '', $required_file);
            }
        }

        return $errors;
    }
}

if (!function_exists('web_manager_connector_admin_notice')) {
    function web_manager_connector_admin_notice() {
        $errors = get_option('web_manager_connector_bootstrap_errors', []);
        if (empty($errors) || !current_user_can('manage_options')) {
            return;
        }

        echo '<div class="notice notice-error"><p><strong>web-manager Connector could not initialize:</strong></p><ul>';
        foreach ($errors as $error) {
            echo '<li>' . esc_html($error) . '</li>';
        }
        echo '</ul></div>';
    }
}

if (!function_exists('web_manager_connector_activate')) {
    function web_manager_connector_activate() {
        $errors = web_manager_connector_dependency_errors();

        if (!empty($errors)) {
            update_option('web_manager_connector_bootstrap_errors', $errors, false);
            web_manager_connector_log('Activation dependency check failed: ' . implode(' | ', $errors));
            return;
        }

        delete_option('web_manager_connector_bootstrap_errors');

        if (!get_option('web_manager_api_key')) {
            if (function_exists('wp_generate_password')) {
                update_option('web_manager_api_key', wp_generate_password(64, false, false), false);
            } else {
                update_option('web_manager_api_key', hash('sha256', uniqid('', true)), false);
            }
        }

        $active_plugins = get_option('active_plugins', []);
        if (is_array($active_plugins)) {
            $current_plugin = plugin_basename(WEB_MANAGER_CONNECTOR_FILE);
            $legacy_plugins = [
                'web-manager/web-manager.php',
                'web-manager-connector/web-manager-connector.php',
                'web-manager-connector/web-manager.php',
                'web-manager/web-manager-connector.php',
            ];
            $legacy_plugins = array_diff($legacy_plugins, [$current_plugin]);

            $filtered_plugins = array_values(array_diff($active_plugins, $legacy_plugins));
            if ($filtered_plugins !== $active_plugins) {
                update_option('active_plugins', $filtered_plugins);
            }
        }
    }
}

if (!function_exists('web_manager_connector_bootstrap')) {
    function web_manager_connector_bootstrap() {
        $errors = web_manager_connector_dependency_errors();

        if (!empty($errors)) {
            update_option('web_manager_connector_bootstrap_errors', $errors, false);
            web_manager_connector_log('Bootstrap stopped: ' . implode(' | ', $errors));
            add_action('admin_notices', 'web_manager_connector_admin_notice');
            return;
        }

        delete_option('web_manager_connector_bootstrap_errors');

        require_once WEB_MANAGER_CONNECTOR_PATH . 'includes/class-api-handler.php';
        require_once WEB_MANAGER_CONNECTOR_PATH . 'includes/class-admin-panel.php';

        if (class_exists('Web_Manager_API_Handler', false)) {
            new Web_Manager_API_Handler();
        } else {
            $errors[] = 'API handler class was not loaded.';
        }

        if (class_exists('Web_Manager_Admin_Panel', false)) {
            new Web_Manager_Admin_Panel(WEB_MANAGER_CONNECTOR_URL, WEB_MANAGER_CONNECTOR_VERSION);
        } else {
            $errors[] = 'Admin panel class was not loaded.';
        }

        if (!empty($errors)) {
            update_option('web_manager_connector_bootstrap_errors', $errors, false);
            web_manager_connector_log('Bootstrap class check failed: ' . implode(' | ', $errors));
            add_action('admin_notices', 'web_manager_connector_admin_notice');
        }
    }

    register_activation_hook(WEB_MANAGER_CONNECTOR_FILE, 'web_manager_connector_activate');
    add_action('plugins_loaded', 'web_manager_connector_bootstrap', 20);
    add_action('admin_notices', 'web_manager_connector_admin_notice');
}
