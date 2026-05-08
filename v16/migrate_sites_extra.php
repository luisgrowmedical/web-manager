<?php
require_once 'config/database.php';
try {
    $pdo->exec("ALTER TABLE sites 
        ADD COLUMN country VARCHAR(100) DEFAULT NULL,
        ADD COLUMN state VARCHAR(100) DEFAULT NULL,
        ADD COLUMN city VARCHAR(100) DEFAULT NULL,
        ADD COLUMN specialty VARCHAR(100) DEFAULT NULL
    ");
    echo "Columns added to 'sites' table.\n";
} catch (PDOException $e) {
    echo "Error or columns already exist: " . $e->getMessage() . "\n";
}
