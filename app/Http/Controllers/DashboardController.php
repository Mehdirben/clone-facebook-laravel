<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Affiche le fil d'actualité de l'utilisateur
     * Montre les publications de l'utilisateur et de ses amis
     */
    public function index()
    {
        // Obtenir l'utilisateur connecté
        $user = Auth::user();
        
        // Récupérer les IDs des amis (utilisateurs avec des demandes acceptées)
        $sentFriendIds = Friend::where('user_id', $user->id)
            ->where('status', 'accepted')
            ->pluck('friend_id')
            ->toArray();
            
        $receivedFriendIds = Friend::where('friend_id', $user->id)
            ->where('status', 'accepted')
            ->pluck('user_id')
            ->toArray();
            
        $friendIds = array_merge($sentFriendIds, $receivedFriendIds);
        
        // Ajouter l'ID de l'utilisateur actuel pour voir aussi ses propres posts
        $friendIds[] = $user->id;
        
        // Récupérer les publications de l'utilisateur et de ses amis
        $posts = Post::whereIn('user_id', $friendIds)
            ->where(function($query) {
                // Montrer les publications publiques ou celles de l'utilisateur actuel
                $query->where('is_public', true)
                    ->orWhere('user_id', Auth::id());
            })
            ->with(['user', 'comments.user', 'likes'])
            ->latest()
            ->paginate(10);
        
        // Suggérer des amis à l'utilisateur
        $suggestedFriends = User::whereNotIn('id', $friendIds)
            ->where('id', '!=', $user->id)
            ->inRandomOrder()
            ->limit(5)
            ->get();
            
        return view('dashboard', compact('posts', 'suggestedFriends'));
    }
}
