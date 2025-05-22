<?php

echo "Testing different PostgreSQL passwords...\n\n";

$host = '127.0.0.1';
$port = '5432';
$dbname = 'postgres'; // Use the default database for testing
$user = 'postgres';

$passwords = [
    '000000',
    'postgres',
    '', // Empty password
    null // Null password
];

foreach ($passwords as $password) {
    try {
        echo "Testing password: " . ($password === null ? 'NULL' : ($password === '' ? 'EMPTY STRING' : $password)) . "\n";
        
        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
        $pdo = new PDO($dsn, $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        echo "✓ Connection successful!\n\n";
        
        // Break out of the loop since we found a working password
        break;
    } catch (PDOException $e) {
        echo "✗ Connection failed: " . $e->getMessage() . "\n\n";
    }
}

// If we found a working password, test the facebook_clone database
if (isset($pdo) && $pdo instanceof PDO) {
    try {
        // Try to connect to the facebook_clone database with the working password
        $dsn = "pgsql:host=$host;port=$port;dbname=facebook_clone";
        $pdo_fb = new PDO($dsn, $user, $password);
        $pdo_fb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        echo "Successfully connected to facebook_clone database!\n";
        
        // Check tables
        $stmt = $pdo_fb->query("SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = 'public'");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        echo "Tables in the facebook_clone database:\n";
        foreach ($tables as $table) {
            echo "- $table\n";
        }
    } catch (PDOException $e) {
        echo "Could not connect to facebook_clone database: " . $e->getMessage() . "\n";
    }
} 