<?php

echo "Testing connection to facebook_clone database with '000000' password...\n";

try {
    $host = '127.0.0.1';
    $port = '5432';
    $dbname = 'facebook_clone';
    $user = 'postgres';
    $password = '000000';
    
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ Connection to facebook_clone successful!\n";
    
    // Check tables
    $stmt = $pdo->query("SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = 'public'");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Tables in the facebook_clone database:\n";
    foreach ($tables as $table) {
        echo "- $table\n";
    }
    
    // Update the Laravel .env file with the correct password
    $envFile = '.env';
    if (file_exists($envFile)) {
        $envContent = file_get_contents($envFile);
        $envContent = preg_replace('/DB_PASSWORD=.*/', 'DB_PASSWORD=000000', $envContent);
        file_put_contents($envFile, $envContent);
        echo "\nUpdated .env file with the correct PostgreSQL password.\n";
    }
    
} catch (PDOException $e) {
    echo "✗ Connection failed: " . $e->getMessage() . "\n";
} 