<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;
use Exception;

class NotificationController extends Controller
{
    /**
     * Afficher toutes les notifications de l'utilisateur
     */
    public function index()
    {
        // Get custom notifications (our own model)
        $notifications = Notification::where('user_id', Auth::id())
            ->with('fromUser')
            ->latest()
            ->paginate(20);
            
        return view('notifications.index', compact('notifications'));
    }
    
    /**
     * Marquer une notification comme lue
     */
    public function markAsRead($notification)
    {
        // Check if it's a Laravel notification (UUID) or custom notification (integer)
        if (is_string($notification) && strlen($notification) === 36) {
            // Laravel notification
            $laravelNotification = DatabaseNotification::where('id', $notification)
                ->where('notifiable_id', Auth::id())
                ->where('notifiable_type', get_class(Auth::user()))
                ->first();
                
            if (!$laravelNotification) {
                if (request()->expectsJson()) {
                    return response()->json(['error' => 'Notification not found'], 404);
                }
                abort(404, 'Notification non trouvée');
            }
            
            $laravelNotification->markAsRead();
            
            if (request()->expectsJson()) {
                return response()->json(['success' => true]);
            }
            
            return redirect()->back()->with('success', 'Notification marquée comme lue.');
        } else {
            // Custom notification
            $customNotification = Notification::where('id', $notification)
                ->where('user_id', Auth::id())
                ->first();
                
            if (!$customNotification) {
                if (request()->expectsJson()) {
                    return response()->json(['error' => 'Notification not found'], 404);
                }
                abort(404, 'Notification non trouvée');
            }
            
            $customNotification->is_read = true;
            $customNotification->save();
            
            if (request()->expectsJson()) {
                return response()->json(['success' => true]);
            }
            
            return redirect()->back()->with('success', 'Notification marquée comme lue.');
        }
    }
    
    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead()
    {
        // Handle both Laravel notifications and custom notifications
        try {
            // Try Laravel notifications first
            if (method_exists(Auth::user(), 'unreadNotifications')) {
                Auth::user()->unreadNotifications->markAsRead();
            }
        } catch (Exception $e) {
            // Fallback to custom notifications if Laravel notifications fail
            Notification::where('user_id', Auth::id())
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }
        
        return response()->json(['success' => true]);
    }
}
