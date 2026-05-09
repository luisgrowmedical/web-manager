<?php
/**
 * Connector Update Controller
 */

class ConnectorUpdateController {
    private $db;
    private $slug = 'web-manager';
    private $plugin_file = 'web-manager/web-manager.php';

    public function __construct($db) {
        $this->db = $db;
    }

    public function manifest() {
        $site = $this->authorize_site();
        if (!$site) {
            $this->json_response(['error' => 'Unauthorized update check.'], 401);
        }

        $manifest = $this->build_manifest($site);
        $this->json_response($manifest);
    }

    public function download() {
        $site = $this->authorize_site();
        if (!$site) {
            $this->json_response(['error' => 'Unauthorized package download.'], 401);
        }

        $package_path = $this->package_path();
        if (!is_readable($package_path)) {
            $this->json_response(['error' => 'Connector package is not available.'], 404);
        }

        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . basename($package_path) . '"');
        header('Content-Length: ' . filesize($package_path));
        header('X-Content-Type-Options: nosniff');
        readfile($package_path);
        exit;
    }

    private function authorize_site() {
        $api_key = $_GET['api_key'] ?? '';
        $site_url = $_GET['site_url'] ?? '';

        if ($api_key === '' || $site_url === '') {
            return null;
        }

        $stmt = $this->db->prepare("SELECT * FROM sites WHERE api_key = ?");
        $stmt->execute([$api_key]);
        $sites = $stmt->fetchAll();

        foreach ($sites as $site) {
            if (!hash_equals((string)$site['api_key'], (string)$api_key)) {
                continue;
            }

            if ($this->urls_match($site['url'], $site_url)) {
                return $site;
            }
        }

        return null;
    }

    private function build_manifest($site) {
        $plugin_file = $this->requested_plugin_file();
        $slug = dirname($plugin_file);
        $plugin_path = dirname(__DIR__, 2) . '/wordpress-plugin/web-manager-connector/web-manager.php';
        $version = $this->read_plugin_header($plugin_path, 'Version') ?: '1.0.0';
        $requires = $this->read_plugin_header($plugin_path, 'Requires at least') ?: '5.8';
        $requires_php = $this->read_plugin_header($plugin_path, 'Requires PHP') ?: '7.4';
        $tested = $this->read_plugin_header($plugin_path, 'Tested up to') ?: '';

        return [
            'slug' => $slug,
            'plugin' => $plugin_file,
            'name' => 'web-manager Connector',
            'version' => $version,
            'new_version' => $version,
            'package' => $this->absolute_url('/web-manager/public/index.php?route=connector-update&action=download&site_url=' . rawurlencode($site['url']) . '&api_key=' . rawurlencode($site['api_key']) . '&plugin_file=' . rawurlencode($plugin_file)),
            'url' => $this->absolute_url('/web-manager/public/index.php?route=dashboard'),
            'requires' => $requires,
            'requires_php' => $requires_php,
            'tested' => $tested,
            'last_updated' => is_readable($plugin_path) ? date('Y-m-d', filemtime($plugin_path)) : date('Y-m-d'),
            'sections' => [
                'description' => 'Connects WordPress with web-manager and enables remote metrics and update synchronization.',
                'changelog' => $this->read_changelog()
            ]
        ];
    }

    private function package_path() {
        $plugin_file = $this->requested_plugin_file();
        $slug = dirname($plugin_file);
        $package_name = $slug === 'web-manager-connector' ? 'web-manager-connector.zip' : 'web-manager.zip';

        return dirname(__DIR__, 2) . '/wordpress-plugin/' . $package_name;
    }

    private function requested_plugin_file() {
        $plugin_file = $_GET['plugin_file'] ?? $this->plugin_file;
        $plugin_file = str_replace('\\', '/', trim((string)$plugin_file));

        $allowed = [
            'web-manager/web-manager.php',
            'web-manager/web-manager-connector.php',
            'web-manager-connector/web-manager.php',
            'web-manager-connector/web-manager-connector.php',
        ];

        return in_array($plugin_file, $allowed, true) ? $plugin_file : $this->plugin_file;
    }

    private function read_plugin_header($file, $header) {
        if (!is_readable($file)) {
            return null;
        }

        $contents = file_get_contents($file, false, null, 0, 8192);
        if ($contents === false) {
            return null;
        }

        if (preg_match('/^\s*\*\s*' . preg_quote($header, '/') . ':\s*(.+)$/mi', $contents, $matches)) {
            return trim($matches[1]);
        }

        return null;
    }

    private function read_changelog() {
        $file = dirname(__DIR__, 2) . '/wordpress-plugin/web-manager-connector/CHANGELOG.md';
        if (!is_readable($file)) {
            return 'Check the central web-manager dashboard for the published version notes.';
        }

        $contents = trim((string)file_get_contents($file));
        return $contents !== '' ? $contents : 'No documented changes.';
    }

    private function urls_match($stored_url, $request_url) {
        $stored = $this->normalize_url($stored_url);
        $request = $this->normalize_url($request_url);

        return $stored !== '' && hash_equals($stored, $request);
    }

    private function normalize_url($url) {
        $url = trim((string)$url);
        if ($url === '') {
            return '';
        }

        if (!preg_match('#^https?://#i', $url)) {
            $url = 'https://' . $url;
        }

        $parts = parse_url($url);
        if (!$parts || empty($parts['host'])) {
            return rtrim(strtolower($url), '/');
        }

        $scheme = strtolower($parts['scheme'] ?? 'https');
        $host = strtolower($parts['host']);
        $path = isset($parts['path']) ? rtrim($parts['path'], '/') : '';

        return $scheme . '://' . $host . $path;
    }

    private function absolute_url($path) {
        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['SERVER_PORT'] ?? null) == 443);
        $scheme = $https ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

        return $scheme . '://' . $host . $path;
    }

    private function json_response($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        header('X-Content-Type-Options: nosniff');
        echo json_encode($data, JSON_UNESCAPED_SLASHES);
        exit;
    }
}
