<?php

echo "PostgreSQL connection test with .env credentials...\n";

try {
    $host = '127.0.0.1';
    $port = '5432';
    $dbname = 'facebook_clone';
    $user = 'postgres';
    $password = '000000';
    
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connection successful!\n";
    
    // Check for users table
    $stmt = $pdo->query("SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = 'public'");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Tables in the database:\n";
    foreach ($tables as $table) {
        echo "- $table\n";
    }
    
    // Count users
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM users");
        $count = $stmt->fetchColumn();
        echo "Number of users: $count\n";
    } catch (PDOException $e) {
        echo "Error counting users: " . $e->getMessage() . "\n";
    }
    
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
} 