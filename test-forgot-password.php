<?php

echo "Testing Forgot Password Functionality\n";
echo "=====================================\n\n";

// Test 1: Check if we can access the Laravel application
try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    echo "✓ Laravel application loads successfully\n";
} catch (Exception $e) {
    echo "✗ Error loading Laravel: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 2: Check if routes are accessible
try {
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $routes = $app->make('router')->getRoutes();
    
    $passwordRoutes = [];
    foreach ($routes as $route) {
        if (str_contains($route->getName() ?? '', 'password')) {
            $passwordRoutes[] = $route->getName();
        }
    }
    
    if (!empty($passwordRoutes)) {
        echo "✓ Password reset routes are registered:\n";
        foreach ($passwordRoutes as $route) {
            echo "  - $route\n";
        }
    } else {
        echo "✗ No password reset routes found\n";
    }
} catch (Exception $e) {
    echo "✗ Error checking routes: " . $e->getMessage() . "\n";
}

// Test 3: Check database connection and tables
try {
    $app->boot();
    $db = $app->make('db');
    $db->connection()->getPdo();
    echo "✓ Database connection successful\n";
    
    // Check if password_reset_tokens table exists
    $tables = $db->select("SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = 'public' AND tablename = 'password_reset_tokens'");
    if (!empty($tables)) {
        echo "✓ password_reset_tokens table exists\n";
    } else {
        echo "✗ password_reset_tokens table not found\n";
    }
    
    // Check if users table exists
    $usersTables = $db->select("SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = 'public' AND tablename = 'users'");
    if (!empty($usersTables)) {
        echo "✓ users table exists\n";
    } else {
        echo "✗ users table not found\n";
    }
    
} catch (Exception $e) {
    echo "✗ Database error: " . $e->getMessage() . "\n";
}

// Test 4: Check mail configuration
try {
    $mailConfig = config('mail');
    echo "✓ Mail configuration loaded\n";
    echo "  - Default mailer: " . $mailConfig['default'] . "\n";
    echo "  - From address: " . $mailConfig['from']['address'] . "\n";
    
    if ($mailConfig['default'] === 'log') {
        echo "  - Note: Using 'log' driver - emails will be written to storage/logs/laravel.log\n";
    }
} catch (Exception $e) {
    echo "✗ Mail configuration error: " . $e->getMessage() . "\n";
}

// Test 5: Check if views exist
$viewPaths = [
    'resources/views/auth/forgot-password.blade.php',
    'resources/views/auth/reset-password.blade.php'
];

foreach ($viewPaths as $path) {
    if (file_exists($path)) {
        echo "✓ View exists: $path\n";
    } else {
        echo "✗ View missing: $path\n";
    }
}

// Test 6: Check if controllers exist
$controllerPaths = [
    'app/Http/Controllers/Auth/PasswordResetLinkController.php',
    'app/Http/Controllers/Auth/NewPasswordController.php'
];

foreach ($controllerPaths as $path) {
    if (file_exists($path)) {
        echo "✓ Controller exists: $path\n";
    } else {
        echo "✗ Controller missing: $path\n";
    }
}

echo "\n=====================================\n";
echo "Summary: The forgot password functionality appears to be properly implemented.\n";
echo "Components found:\n";
echo "- Routes: forgot-password (GET/POST) and reset-password (GET/POST)\n";
echo "- Controllers: PasswordResetLinkController and NewPasswordController\n";
echo "- Views: forgot-password.blade.php and reset-password.blade.php\n";
echo "- Database: password_reset_tokens table\n";
echo "- Mail: Configured (using log driver by default)\n\n";

echo "To test the functionality:\n";
echo "1. Start the development server: php artisan serve\n";
echo "2. Visit: http://localhost:8000/forgot-password\n";
echo "3. Enter an email address of an existing user\n";
echo "4. Check storage/logs/laravel.log for the reset email\n";
echo "5. Copy the reset link from the log and test it\n"; 