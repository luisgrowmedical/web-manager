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

/**
 * Build an app-relative asset URL that works when web-manager is deployed in a subfolder.
 */
function wm_asset_url($path) {
    $public_base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
    if ($public_base === '/' || $public_base === '.') {
        $public_base = '';
    }

    return $public_base . '/assets/' . ltrim($path, '/');
}

/**
 * Normalize country names for matching values stored by forms with config/nations.txt.
 */
function wm_normalize_country_key($country) {
    $country = trim((string) $country);
    $country = html_entity_decode($country, ENT_QUOTES, 'UTF-8');

    if (function_exists('iconv')) {
        $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $country);
        if ($converted !== false) {
            $country = $converted;
        }
    }

    $country = strtolower($country);
    $country = preg_replace('/[^a-z0-9]+/', ' ', $country);

    return trim($country);
}

/**
 * Load the canonical country -> flag mapping from config/nations.txt.
 */
function wm_nations() {
    static $nations = null;

    if ($nations !== null) {
        return $nations;
    }

    $nations = [
        'by_name' => [],
        'by_code' => []
    ];

    $file = __DIR__ . '/../../config/nations.txt';
    if (!is_readable($file)) {
        return $nations;
    }

    $contents = file_get_contents($file);
    if ($contents === false) {
        return $nations;
    }

    if (strpos($contents, "\0") !== false && function_exists('iconv')) {
        $converted = @iconv('UTF-16LE', 'UTF-8//IGNORE', $contents);
        if ($converted !== false) {
            $contents = $converted;
        }
    }

    $rows = preg_split('/\r\n|\r|\n/', $contents);
    array_shift($rows);

    foreach ($rows as $line) {
        if (trim($line) === '') {
            continue;
        }

        $row = str_getcsv($line, "\t");
        if (count($row) < 7) {
            continue;
        }

        $code = trim($row[0]);
        $name = trim($row[1]);
        $nation_id = trim($row[6]);

        if ($name === '' || $nation_id === '') {
            continue;
        }

        $flag_path = wm_asset_url('img/countryflags/f' . $nation_id . '.png');
        $info = [
            'code' => $code,
            'name' => $name,
            'nation_id' => $nation_id,
            'flag_url' => $flag_path
        ];

        $nations['by_name'][wm_normalize_country_key($name)] = $info;
        if ($code !== '' && !isset($nations['by_code'][$code])) {
            $nations['by_code'][$code] = $info;
        }
    }

    $aliases = [
        'usa' => 'US',
        'u s a' => 'US',
        'united states of america' => 'US',
        'united kingdom' => 'GB',
        'uk' => 'GB'
    ];

    foreach ($aliases as $alias => $code) {
        if (isset($nations['by_code'][$code])) {
            $nations['by_name'][wm_normalize_country_key($alias)] = $nations['by_code'][$code];
        }
    }

    return $nations;
}

/**
 * Return flag metadata for a country value, falling back to a safe placeholder.
 */
function wm_country_flag_info($country) {
    $nations = wm_nations();
    $key = wm_normalize_country_key($country);

    if ($key !== '' && isset($nations['by_name'][$key])) {
        return $nations['by_name'][$key];
    }

    return [
        'code' => '',
        'name' => (string) $country,
        'nation_id' => '',
        'flag_url' => wm_asset_url('img/countryflags/notfound.png')
    ];
}

/**
 * Render a country option without changing its submitted value.
 */
function wm_country_option($value, $label = null, $selected = false) {
    $label = $label ?? $value;
    $flag = wm_country_flag_info($label);

    return sprintf(
        '<option value="%s"%s data-flag-src="%s" data-flag-fallback="%s" data-flag-alt="%s">%s</option>',
        htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'),
        $selected ? ' selected' : '',
        htmlspecialchars($flag['flag_url'], ENT_QUOTES, 'UTF-8'),
        htmlspecialchars(wm_asset_url('img/countryflags/notfound.png'), ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($label . ' flag', ENT_QUOTES, 'UTF-8'),
        htmlspecialchars((string) $label, ENT_QUOTES, 'UTF-8')
    );
}
