<?php
require_once 'config/database.php';

try {
    $stmt = $pdo->query("SHOW COLUMNS FROM site_metrics LIKE 'pending_updates_data'");
    if ($stmt->fetch()) {
        echo "Column 'pending_updates_data' already exists.\n";
        exit;
    }

    $pdo->exec("ALTER TABLE site_metrics ADD COLUMN pending_updates_data LONGTEXT AFTER updates_count");
    echo "Column 'pending_updates_data' added to 'site_metrics' table.\n";
} catch (PDOException $e) {
    echo "Error adding pending_updates_data: " . $e->getMessage() . "\n";
}
