<?php
require_once __DIR__ . '/config/database.php';

$columns = $pdo->query("SHOW COLUMNS FROM site_metrics")->fetchAll(PDO::FETCH_COLUMN);

$updates = [
    'is_multisite' => "ALTER TABLE site_metrics ADD COLUMN is_multisite TINYINT(1) DEFAULT 0 AFTER connector_version",
    'multisite_network_name' => "ALTER TABLE site_metrics ADD COLUMN multisite_network_name VARCHAR(255) DEFAULT NULL AFTER is_multisite",
    'multisite_site_count' => "ALTER TABLE site_metrics ADD COLUMN multisite_site_count INT DEFAULT 0 AFTER multisite_network_name",
];

foreach ($updates as $column => $sql) {
    if (!in_array($column, $columns, true)) {
        $pdo->exec($sql);
        echo "Added {$column}\n";
    }
}

echo "Multisite metrics migration complete.\n";
