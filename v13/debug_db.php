<?php
require_once 'config/database.php';
echo "--- SITES ---\n";
$stmt = $pdo->query("SELECT * FROM sites");
print_r($stmt->fetchAll());

echo "\n--- USERS ---\n";
$stmt = $pdo->query("SELECT u.*, r.name as role_name FROM users u JOIN roles r ON u.role_id = r.id");
print_r($stmt->fetchAll());

echo "\n--- ACCESS ---\n";
$stmt = $pdo->query("SELECT * FROM site_user_access");
print_r($stmt->fetchAll());
