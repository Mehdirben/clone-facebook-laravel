<?php

echo "Testing PostgreSQL Connection...\n";

$dbParams = [
    'host' => '127.0.0.1',
    'port' => '5432',
    'dbname' => 'postgres', // Using default 'postgres' database for test
    'user' => 'postgres',
    'password' => '000000' // Updated password to match what's in .env
];

try {
    // Try PDO connection
    echo "Testing PDO Connection...\n";
    $dsn = "pgsql:host={$dbParams['host']};port={$dbParams['port']};dbname={$dbParams['dbname']}";
    $pdo = new PDO($dsn, $dbParams['user'], $dbParams['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "PDO Connection successful!\n";
    
    // Get PostgreSQL version
    $stmt = $pdo->query('SELECT version()');
    $version = $stmt->fetchColumn();
    echo "PostgreSQL Version: $version\n";
    
    // Check if facebook_clone database exists
    $stmt = $pdo->query("SELECT datname FROM pg_database WHERE datname = 'facebook_clone'");
    if ($stmt->fetchColumn()) {
        echo "Database 'facebook_clone' already exists.\n";
    } else {
        echo "Creating 'facebook_clone' database...\n";
        $pdo->exec("CREATE DATABASE facebook_clone");
        echo "Database 'facebook_clone' created successfully!\n";
    }
    
    // Close the connection
    $pdo = null;
    
} catch (PDOException $e) {
    echo "PDO Connection Failed: " . $e->getMessage() . "\n";
    
    // Try native pg connection as fallback
    echo "\nTrying native pg_connect as fallback...\n";
    try {
        $connStr = "host={$dbParams['host']} port={$dbParams['port']} dbname={$dbParams['dbname']} user={$dbParams['user']} password={$dbParams['password']}";
        $conn = pg_connect($connStr);
        
        if ($conn) {
            echo "Native pg_connect successful!\n";
            $result = pg_query($conn, "SELECT version()");
            $version = pg_fetch_result($result, 0, 0);
            echo "PostgreSQL Version: $version\n";
            
            // Check if facebook_clone database exists
            $result = pg_query($conn, "SELECT datname FROM pg_database WHERE datname = 'facebook_clone'");
            if (pg_num_rows($result) > 0) {
                echo "Database 'facebook_clone' already exists.\n";
            } else {
                echo "Creating 'facebook_clone' database...\n";
                pg_query($conn, "CREATE DATABASE facebook_clone");
                echo "Database 'facebook_clone' created successfully!\n";
            }
            
            pg_close($conn);
        } else {
            echo "Native pg_connect failed.\n";
        }
    } catch (Exception $e) {
        echo "Native pg_connect Failed: " . $e->getMessage() . "\n";
    }
}

echo "\nConnection test completed.\n"; 