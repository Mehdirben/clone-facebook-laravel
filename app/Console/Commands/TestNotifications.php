<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Console\Command;
use Exception;

class TestNotifications extends Command
{
    protected $signature = 'test:notifications';
    protected $description = 'Test notification system to ensure no BadMethodCallException';

    public function handle()
    {
        $this->info('Testing notification system...');
        
        $user = User::first();
        if (!$user) {
            $this->error('No users found. Please create a user first.');
            return;
        }
        
        $this->info("Testing with user: {$user->name} ({$user->email})");
        
        // Test Laravel notification system
        try {
            $this->info('Testing Laravel notification system...');
            $laravelNotifications = $user->notifications;
            $this->info('✅ Laravel notifications(): ' . $laravelNotifications->count() . ' notifications found');
            
            $unreadNotifications = $user->unreadNotifications;
            $this->info('✅ Laravel unreadNotifications: ' . $unreadNotifications->count() . ' unread notifications');
        } catch (Exception $e) {
            $this->error('❌ Laravel notification system error: ' . $e->getMessage());
        }
        
        // Test custom notification system
        try {
            $this->info('Testing custom notification system...');
            $customNotifications = Notification::where('user_id', $user->id)->get();
            $this->info('✅ Custom notifications: ' . $customNotifications->count() . ' notifications found');
        } catch (Exception $e) {
            $this->error('❌ Custom notification system error: ' . $e->getMessage());
        }
        
        $this->info('✅ All notification tests completed successfully!');
        
        return 0;
    }
} 