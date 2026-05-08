<?php
require_once 'config/database.php';
try {
    $pdo->exec("ALTER TABLE site_metrics 
        ADD COLUMN plugins_data LONGTEXT DEFAULT NULL,
        ADD COLUMN themes_data LONGTEXT DEFAULT NULL,
        ADD COLUMN pending_updates_data LONGTEXT DEFAULT NULL
    ");
    echo "Inventory columns added to 'site_metrics' table.\n";
} catch (PDOException $e) {
    echo "Error or columns already exist: " . $e->getMessage() . "\n";
}
