<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    /**
     * Afficher toutes les conversations de l'utilisateur
     */
    public function index()
    {
        $user = Auth::user();
        
        // Récupérer les utilisateurs avec lesquels l'utilisateur actuel a eu des conversations
        $conversations = Message::where('user_id', $user->id)
            ->orWhere('recipient_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($message) use ($user) {
                // Récupérer l'id de l'autre utilisateur de la conversation
                $otherUserId = $message->user_id == $user->id ? $message->recipient_id : $message->user_id;
                return $otherUserId;
            })
            ->unique()
            ->values();
            
        $users = User::whereIn('id', $conversations)->get();
        
        // Compter les messages non lus pour chaque conversation
        $unreadCounts = [];
        foreach ($users as $otherUser) {
            $unreadCounts[$otherUser->id] = Message::where('user_id', $otherUser->id)
                ->where('recipient_id', $user->id)
                ->where('is_read', false)
                ->count();
        }
        
        return view('messages.index', compact('users', 'unreadCounts'));
    }
    
    /**
     * Afficher une conversation avec un utilisateur spécifique
     */
    public function show(User $user)
    {
        // Vérifier que l'utilisateur ne tente pas de discuter avec lui-même
        if ($user->id === Auth::id()) {
            abort(403, 'Vous ne pouvez pas discuter avec vous-même.');
        }
        
        $authUser = Auth::user();
        
        // Récupérer tous les messages entre les deux utilisateurs
        $messages = Message::where(function($query) use ($user, $authUser) {
                $query->where('user_id', $authUser->id)->where('recipient_id', $user->id);
            })
            ->orWhere(function($query) use ($user, $authUser) {
                $query->where('user_id', $user->id)->where('recipient_id', $authUser->id);
            })
            ->orderBy('created_at', 'asc')
            ->get();
            
        // Marquer les messages reçus comme lus
        Message::where('user_id', $user->id)
            ->where('recipient_id', $authUser->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
            
        return view('messages.show', compact('user', 'messages'));
    }
    
    /**
     * Envoyer un message à un utilisateur
     */
    public function send(Request $request, User $user)
    {
        // Vérifier que l'utilisateur ne tente pas d'envoyer un message à lui-même
        if ($user->id === Auth::id()) {
            abort(403, 'Vous ne pouvez pas vous envoyer de message.');
        }
        
        $request->validate([
            'content' => 'required|string',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ]);
        
        $message = new Message();
        $message->user_id = Auth::id();
        $message->recipient_id = $user->id;
        $message->content = $request->content;
        
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('messages/attachments', 'public');
            $message->attachment = $attachmentPath;
        }
        
        $message->save();
        
        return redirect()->route('messages.show', $user)->with('success', 'Message envoyé avec succès !');
    }
    
    /**
     * Marquer un message comme lu
     */
    public function markAsRead(Message $message)
    {
        // Vérifier que l'utilisateur est bien le destinataire du message
        if ($message->recipient_id !== Auth::id()) {
            abort(403, 'Non autorisé');
        }
        
        $message->markAsRead();
        
        return response()->json(['success' => true]);
    }
}
