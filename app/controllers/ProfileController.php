<?php
/**
 * Profile Controller
 */

class ProfileController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function index() {
        $user_id = $_SESSION['user_id'];
        
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $avatar_path = $user['avatar'];

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
                        $_SESSION['avatar'] = $avatar_path;
                    }
                }
            }

            try {
                // Update Password if provided
                if (!empty($_POST['new_password'])) {
                    $password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                    $stmt = $this->db->prepare("UPDATE users SET username = ?, email = ?, password = ?, avatar = ? WHERE id = ?");
                    $stmt->execute([$username, $email, $password, $avatar_path, $user_id]);
                } else {
                    $stmt = $this->db->prepare("UPDATE users SET username = ?, email = ?, avatar = ? WHERE id = ?");
                    $stmt->execute([$username, $email, $avatar_path, $user_id]);
                }

                $_SESSION['username'] = $username;
                set_message("Profile updated successfully!");
                redirect('/web-manager/public/index.php?route=profile');
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    set_message("Username or Email already exists.", "error");
                } else {
                    set_message("Database error: " . $e->getMessage(), "error");
                }
            }
        }

        view('profile/index', [
            'title' => 'My Profile',
            'user' => $user
        ]);
    }
}
