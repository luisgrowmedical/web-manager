<?php
require_once 'config/database.php';
try {
    $pdo->exec("ALTER TABLE users ADD COLUMN avatar VARCHAR(255) DEFAULT NULL");
    echo "Column 'avatar' added to 'users' table.\n";
} catch (PDOException $e) {
    echo "Error or column already exists: " . $e->getMessage() . "\n";
}
