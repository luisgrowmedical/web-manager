<?php
/**
 * Main Entry Point / Router
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/helpers/functions.php';

// Debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Controllers
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/DashboardController.php';
require_once __DIR__ . '/../app/controllers/SiteController.php';
require_once __DIR__ . '/../app/controllers/AdminController.php';
require_once __DIR__ . '/../app/controllers/ConnectorUpdateController.php';

$route = $_GET['route'] ?? 'dashboard';
$action = $_GET['action'] ?? 'index';

// Simple Router
switch ($route) {
    case 'login':
        $controller = new AuthController($pdo);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->login();
        } else {
            $controller->showLogin();
        }
        break;

    case 'logout':
        $controller = new AuthController($pdo);
        $controller->logout();
        break;

    case 'connector-update':
        $controller = new ConnectorUpdateController($pdo);
        if ($action === 'download') {
            $controller->download();
        } else {
            $controller->manifest();
        }
        break;

    case 'dashboard':
        require_login();
        $controller = new DashboardController($pdo);
        $controller->index();
        break;

    case 'profile':
        require_login();
        require_once '../app/controllers/ProfileController.php';
        $controller = new ProfileController($pdo);
        $controller->index();
        break;

    case 'settings':
        require_admin();
        $controller = new AdminController($pdo);
        $controller->settings();
        break;

    case 'sites':
        require_login();
        $controller = new SiteController($pdo);
        if ($action === 'add') {
            $controller->add();
        } elseif ($action === 'view') {
            $controller->view($_GET['id']);
        } elseif ($action === 'sync') {
            $controller->sync($_GET['id']);
        } elseif ($action === 'delete') {
            $controller->delete($_GET['id']);
        } else {
            $controller->index();
        }
        break;

    case 'admin':
        require_admin();
        $controller = new AdminController($pdo);
        if ($action === 'users') {
            $controller->users();
        } elseif ($action === 'access') {
            $controller->manageAccess($_GET['id']);
        } elseif ($action === 'delete_user') {
            $controller->deleteUser($_GET['id']);
        } else {
            $controller->index();
        }
        break;

    default:
        redirect('/web-manager/public/index.php?route=dashboard');
        break;
}
