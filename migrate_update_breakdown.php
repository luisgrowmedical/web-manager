<?php
require_once 'config/database.php';

try {
    $pdo->exec("ALTER TABLE site_metrics
        ADD COLUMN plugin_updates_count INT DEFAULT 0,
        ADD COLUMN theme_updates_count INT DEFAULT 0
    ");
    echo "Update breakdown columns added to 'site_metrics' table.\n";
} catch (PDOException $e) {
    echo "Error or columns already exist: " . $e->getMessage() . "\n";
}
