<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->boot();

echo "Testing Mailtrap Connection\n";
echo "==========================\n\n";

try {
    // Test mail configuration
    $mailConfig = config('mail');
    echo "✓ Mail configuration loaded\n";
    echo "  - Driver: " . $mailConfig['default'] . "\n";
    echo "  - Host: " . $mailConfig['mailers']['smtp']['host'] . "\n";
    echo "  - Port: " . $mailConfig['mailers']['smtp']['port'] . "\n";
    echo "  - Username: " . ($mailConfig['mailers']['smtp']['username'] ? 'Set' : 'Not set') . "\n";
    echo "  - Password: " . ($mailConfig['mailers']['smtp']['password'] ? 'Set' : 'Not set') . "\n\n";
    
    // Try to send a test email
    echo "Testing email sending...\n";
    
    try {
        \Illuminate\Support\Facades\Mail::raw('This is a test email from your Laravel application.', function ($message) {
            $message->to('test@example.com')
                    ->subject('Test Email - Mailtrap Configuration');
        });
        
        echo "✅ Email sent successfully!\n";
        echo "✅ Check your Mailtrap inbox to see the email.\n";
    } catch (Exception $e) {
        echo "❌ Failed to send email: " . $e->getMessage() . "\n";
        
        // Additional debugging
        if (strpos($e->getMessage(), 'Connection could not be established') !== false) {
            echo "\n🔍 Connection issue detected. Possible causes:\n";
            echo "  - Wrong username/password\n";
            echo "  - Wrong host/port\n";
            echo "  - Firewall blocking connection\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Configuration error: " . $e->getMessage() . "\n";
}

echo "\n==========================\n";
echo "Test complete!\n"; 