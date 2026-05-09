<?php
require_once 'config/database.php';

try {
    $stmt = $pdo->query("SHOW COLUMNS FROM site_metrics LIKE 'connector_version'");
    if ($stmt->fetch()) {
        echo "Column 'connector_version' already exists.\n";
        exit;
    }

    $pdo->exec("ALTER TABLE site_metrics ADD COLUMN connector_version VARCHAR(50) DEFAULT NULL AFTER pending_updates_data");
    echo "Column 'connector_version' added to 'site_metrics' table.\n";
} catch (PDOException $e) {
    echo "Error adding connector_version: " . $e->getMessage() . "\n";
}
