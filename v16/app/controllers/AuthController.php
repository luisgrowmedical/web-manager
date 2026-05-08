<?php
/**
 * Auth Controller
 */

class AuthController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function showLogin() {
        if (is_logged_in()) {
            redirect('/web-manager/public/index.php?route=dashboard');
        }
        // Simplified view call for login (no header/footer layout)
        require_once __DIR__ . '/../views/login.php';
    }

    public function login() {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $stmt = $this->db->prepare("
            SELECT u.*, r.name as role_name 
            FROM users u 
            JOIN roles r ON u.role_id = r.id 
            WHERE u.username = ?
        ");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role_name'];
            $_SESSION['avatar'] = $user['avatar'];
            redirect('/web-manager/public/index.php?route=dashboard');
        } else {
            $error = "Invalid username or password";
            require_once __DIR__ . '/../views/login.php';
        }
    }

    public function logout() {
        session_destroy();
        redirect('/web-manager/public/index.php?route=login');
    }
}
