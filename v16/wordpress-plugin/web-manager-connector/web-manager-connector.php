<?php
/**
 * Plugin Name: web-manager Connector
 * Description: Connects your WordPress site to the web-manager platform.
 * Version: 1.1.1
 * Author: web-manager Team
 */

if (!defined('ABSPATH')) exit;

// Define Constants
define('WEB_MANAGER_PATH', plugin_dir_path(__FILE__));
define('WEB_MANAGER_URL', plugin_dir_url(__FILE__));
define('WEB_MANAGER_VERSION', '1.1.1');

// Load Includes
require_once WEB_MANAGER_PATH . 'includes/class-api-handler.php';
require_once WEB_MANAGER_PATH . 'includes/class-admin-panel.php';

if (!class_exists('Web_Manager_Connector_v111')) {
    class Web_Manager_Connector_v111 {
        public static function init() {
            if (class_exists('Web_Manager_API_Handler')) {
                new \Web_Manager_API_Handler();
            }
            if (is_admin() && class_exists('Web_Manager_Admin_Panel')) {
                new \Web_Manager_Admin_Panel();
            }
        }
    }
}

add_action('plugins_loaded', ['Web_Manager_Connector_v111', 'init']);
