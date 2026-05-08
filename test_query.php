<?php
require_once 'config/database.php';
session_start();
$_SESSION['role'] = 'admin'; // Simulate admin login
$is_admin = true;

$query = $is_admin ? 
    "SELECT s.*, m.pages_count, m.posts_count, m.drafts_count, m.images_count, m.site_weight, m.sync_date 
     FROM sites s LEFT JOIN site_metrics m ON s.id = m.site_id" : "SELECT 1";

$stmt = $pdo->query($query);
$results = $stmt->fetchAll();
echo "Query results: " . count($results) . "\n";
print_r($results);
