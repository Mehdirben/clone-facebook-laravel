<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Afficher toutes les notifications de l'utilisateur
     */
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->with('fromUser')
            ->latest()
            ->paginate(20);
            
        return view('notifications.index', compact('notifications'));
    }
    
    /**
     * Marquer une notification comme lue
     */
    public function markAsRead(Notification $notification)
    {
        // Vérifier que la notification appartient à l'utilisateur
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Non autorisé');
        }
        
        $notification->is_read = true;
        $notification->save();
        
        return redirect()->back()->with('success', 'Notification marquée comme lue.');
    }
    
    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        return redirect()->back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }
}
