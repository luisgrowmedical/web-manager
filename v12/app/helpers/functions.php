<?php
/**
 * Global Helper Functions
 */

session_start();

/**
 * Redirect to a specific path
 */
function redirect($path) {
    header("Location: " . $path);
    exit;
}

/**
 * Check if user is logged in
 */
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

/**
 * Require login for a page
 */
function require_login() {
    if (!is_logged_in()) {
        redirect('/web-manager/public/index.php?route=login');
    }
}

/**
 * Require admin role
 */
function require_admin() {
    require_login();
    if ($_SESSION['role'] !== 'admin') {
        redirect('/web-manager/public/index.php?route=dashboard');
    }
}

/**
 * Render a view
 */
function view($name, $data = []) {
    extract($data);
    require_once __DIR__ . '/../views/layout/header.php';
    require_once __DIR__ . '/../views/' . $name . '.php';
    require_once __DIR__ . '/../views/layout/footer.php';
}

/**
 * CSRF Token generation
 */
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF Token
 */
function verify_csrf($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Get current user ID
 */
function current_user_id() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Set a flash message
 */
function set_message($text, $type = 'success') {
    $_SESSION['flash_message'] = [
        'text' => $text,
        'type' => $type
    ];
}

/**
 * Get and clear flash message
 */
function get_message() {
    if (isset($_SESSION['flash_message'])) {
        $msg = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $msg;
    }
    return null;
}
