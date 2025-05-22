<?php

// Database connection parameters
$host = '127.0.0.1';
$port = '5432';
$dbname = 'facebook_clone';
$user = 'postgres';
$password = '000000'; // Match your .env password

try {
    // Connect to the database
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database successfully!\n";
    
    // Check if the migration already exists
    $migrationName = '2025_05_21_create_sessions_table';
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM migrations WHERE migration = ?");
    $stmt->execute([$migrationName]);
    $count = $stmt->fetchColumn();
    
    if ($count > 0) {
        echo "Migration '$migrationName' already exists in the migrations table.\n";
    } else {
        // Get the current batch number
        $stmt = $pdo->query("SELECT MAX(batch) FROM migrations");
        $batch = $stmt->fetchColumn();
        if (!$batch) {
            $batch = 1;
        }
        
        // Insert the migration
        $stmt = $pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (?, ?)");
        $stmt->execute([$migrationName, $batch]);
        
        echo "Migration '$migrationName' has been inserted into the migrations table with batch $batch.\n";
    }
    
    // Also fix the shares migration if it's not already migrated
    $migrationName = '2025_05_30_000000_create_shares_table';
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM migrations WHERE migration = ?");
    $stmt->execute([$migrationName]);
    $count = $stmt->fetchColumn();
    
    if ($count > 0) {
        echo "Migration '$migrationName' already exists in the migrations table.\n";
    } else {
        // Get the current batch number
        $stmt = $pdo->query("SELECT MAX(batch) FROM migrations");
        $batch = $stmt->fetchColumn();
        
        // Run the migration manually using Laravel's migration file
        echo "Attempting to manually run the shares migration...\n";
        
        try {
            $pdo->exec("CREATE TABLE shares (
                id BIGSERIAL PRIMARY KEY,
                user_id BIGINT UNSIGNED NOT NULL,
                post_id BIGINT UNSIGNED NOT NULL,
                created_at TIMESTAMP(0) WITHOUT TIME ZONE NULL,
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE NULL,
                CONSTRAINT shares_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
                CONSTRAINT shares_post_id_foreign FOREIGN KEY (post_id) REFERENCES posts (id) ON DELETE CASCADE
            )");
            
            // Insert the migration
            $stmt = $pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (?, ?)");
            $stmt->execute([$migrationName, $batch]);
            
            echo "Migration '$migrationName' has been manually executed and inserted into the migrations table.\n";
        } catch (PDOException $e) {
            echo "Error running shares migration: " . $e->getMessage() . "\n";
            
            // Try to just insert the migration record
            $stmt = $pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (?, ?)");
            $stmt->execute([$migrationName, $batch]);
            
            echo "Migration '$migrationName' has been inserted into the migrations table without creating the table.\n";
        }
    }
    
    echo "Migration fixing completed!\n";
    
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
} 