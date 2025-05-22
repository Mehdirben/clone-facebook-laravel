<?php

// Include Laravel's autoloader
require __DIR__.'/vendor/autoload.php';

// Load environment variables
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing Laravel PostgreSQL connection...\n";

try {
    // Get the current database connection
    $db = Illuminate\Support\Facades\DB::connection();
    
    echo "Connection config: " . json_encode($db->getConfig(), JSON_PRETTY_PRINT) . "\n";
    echo "Laravel is connected to database: " . $db->getDatabaseName() . "\n";
    
    // Test a query
    $tables = Illuminate\Support\Facades\DB::select("SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = 'public'");
    
    echo "\nTables in the database:\n";
    foreach ($tables as $table) {
        echo "- " . $table->tablename . "\n";
    }
    
    // Test model operations
    $userCount = Illuminate\Support\Facades\DB::table('users')->count();
    echo "\nNumber of users: $userCount\n";
    
    echo "\nDatabase connection test completed successfully!\n";
    
} catch (Exception $e) {
    echo "Database connection error: " . $e->getMessage() . "\n";
    
    // Get more detailed information
    echo "\nAdditional debugging information:\n";
    echo "Database driver: " . config('database.default') . "\n";
    echo "Database config: " . json_encode(config('database.connections.' . config('database.default')), JSON_PRETTY_PRINT) . "\n";
} 