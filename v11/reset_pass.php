<?php
require_once 'config/database.php';

$username = 'admin';
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = ?");
$stmt->execute([$hash, $username]);

echo "Password for 'admin' updated to: admin123\n";
echo "New hash: " . $hash . "\n";
