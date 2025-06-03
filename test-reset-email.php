<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->boot();

echo "Testing Password Reset Email Functionality\n";
echo "==========================================\n\n";

try {
    // Test mail configuration
    $mailConfig = config('mail.default');
    echo "✓ Mail driver: " . $mailConfig . "\n";
    
    // Check if we can create a test user
    $user = App\Models\User::first();
    if ($user) {
        echo "✓ Found test user: " . $user->email . "\n";
        
        // Test the password reset link generation
        echo "\nTesting password reset link generation...\n";
        
        $status = Illuminate\Support\Facades\Password::sendResetLink(['email' => $user->email]);
        
        if ($status == Illuminate\Support\Facades\Password::RESET_LINK_SENT) {
            echo "✓ Password reset link sent successfully!\n";
            echo "✓ Check the log file: storage/logs/laravel.log\n";
        } else {
            echo "✗ Failed to send reset link: " . $status . "\n";
        }
    } else {
        echo "✗ No users found in database. Please register a user first.\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\n==========================================\n";
echo "Password Reset Test Complete!\n";
echo "If successful, check storage/logs/laravel.log for the email content.\n"; 