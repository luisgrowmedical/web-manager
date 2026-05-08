<?php
/**
 * Admin Controller
 */

class AdminController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function index() {
        // Redirect to users management as default admin view
        $this->users();
    }

    public function users() {
        $stmt = $this->db->query("
            SELECT u.*, r.name as role_name 
            FROM users u 
            JOIN roles r ON u.role_id = r.id
        ");
        $users = $stmt->fetchAll();

        $stmt = $this->db->query("SELECT * FROM roles");
        $roles = $stmt->fetchAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $role_id = $_POST['role_id'];
            $avatar_path = null;

            // Handle Avatar Upload
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $filename = $_FILES['avatar']['name'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                
                if (in_array($ext, $allowed)) {
                    $new_name = 'avatar_' . time() . '_' . $username . '.' . $ext;
                    
                    // Use absolute-like path from the project root
                    $target_dir = __DIR__ . '/../../public/uploads/avatars/';
                    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
                    
                    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $target_dir . $new_name)) {
                        $avatar_path = '/web-manager/public/uploads/avatars/' . $new_name;
                    }
                }
            }

            try {
                $stmt = $this->db->prepare("INSERT INTO users (username, email, password, role_id, avatar) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$username, $email, $password, $role_id, $avatar_path]);
                
                set_message("User created successfully!");
                redirect('/web-manager/public/index.php?route=admin&action=users');
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    set_message("Username or Email already exists.", "error");
                } else {
                    set_message("Database error: " . $e->getMessage(), "error");
                }
            }
        }

        view('admin/users', [
            'title' => 'Manage Users',
            'users' => $users,
            'roles' => $roles
        ]);
    }

    public function manageAccess($user_id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Clear existing access
            $stmt = $this->db->prepare("DELETE FROM site_user_access WHERE user_id = ?");
            $stmt->execute([$user_id]);

            // Add new access
            if (isset($_POST['sites'])) {
                $stmt = $this->db->prepare("INSERT INTO site_user_access (user_id, site_id) VALUES (?, ?)");
                foreach ($_POST['sites'] as $site_id) {
                    $stmt->execute([$user_id, $site_id]);
                }
            }
            redirect('/web-manager/public/index.php?route=admin&action=users');
        }

        $stmt = $this->db->query("SELECT * FROM sites");
        $all_sites = $stmt->fetchAll();

        $stmt = $this->db->prepare("SELECT site_id FROM site_user_access WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $current_access = $stmt->fetchAll(PDO::FETCH_COLUMN);

        view('admin/access', [
            'title' => 'Manage Site Access for ' . $user['username'],
            'user' => $user,
            'all_sites' => $all_sites,
            'current_access' => $current_access
        ]);
    }

    public function deleteUser($id) {
        require_admin();
        
        // Prevent deleting yourself
        if ($id == $_SESSION['user_id']) {
            set_message("You cannot delete your own account!", "error");
            redirect('/web-manager/public/index.php?route=admin&action=users');
        }

        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        
        set_message("User deleted successfully!");
        redirect('/web-manager/public/index.php?route=admin&action=users');
    }
}
