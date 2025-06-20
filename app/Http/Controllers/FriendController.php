<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    /**
     * Afficher la liste des amis de l'utilisateur
     */
    public function index()
    {
        $user = Auth::user();
        
        // Récupère toutes les demandes d'amitié
        $sentRequests = Friend::where('user_id', $user->id)->with('friend')->get();
        $receivedRequests = Friend::where('friend_id', $user->id)->with('user')->get();
        
        // Amis acceptés
        $acceptedSentFriends = Friend::where('user_id', $user->id)
            ->where('status', 'accepted')
            ->with('friend')
            ->get();
            
        $acceptedReceivedFriends = Friend::where('friend_id', $user->id)
            ->where('status', 'accepted')
            ->with('user')
            ->get();
        
        return view('friends.index', compact('sentRequests', 'receivedRequests', 'acceptedSentFriends', 'acceptedReceivedFriends'));
    }
    
    /**
     * Envoyer une demande d'ami
     */
    public function sendRequest(User $user)
    {
        // Vérifier que l'utilisateur n'envoie pas une demande à lui-même
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas vous envoyer une demande d\'ami.');
        }
        
        // Vérifier si une demande d'ami existe déjà
        $existingRequest = Friend::where(function($query) use ($user) {
                $query->where('user_id', Auth::id())->where('friend_id', $user->id);
            })
            ->orWhere(function($query) use ($user) {
                $query->where('user_id', $user->id)->where('friend_id', Auth::id());
            })
            ->first();
            
        if ($existingRequest) {
            return redirect()->back()->with('error', 'Une demande d\'ami existe déjà.');
        }
        
        // Créer une nouvelle demande d'ami
        $friendRequest = Friend::create([
            'user_id' => Auth::id(),
            'friend_id' => $user->id,
            'status' => 'pending'
        ]);
        
        // Créer une notification pour le destinataire
        Notification::create([
            'user_id' => $user->id, // Destinataire
            'from_user_id' => Auth::id(), // Expéditeur
            'type' => 'friend_request',
            'notifiable_id' => $friendRequest->id,
            'notifiable_type' => Friend::class,
            'content' => Auth::user()->name . ' vous a envoyé une demande d\'ami',
        ]);
        
        return redirect()->back()->with('success', 'Demande d\'ami envoyée avec succès !');
    }
    
    /**
     * Accepter une demande d'ami
     */
    public function acceptRequest(Friend $friend)
    {
        // Vérifier que l'utilisateur est bien le destinataire de la demande d'ami
        if ($friend->friend_id !== Auth::id()) {
            abort(403, 'Non autorisé');
        }
        
        // Mettre à jour le statut de la demande d'ami
        $friend->status = 'accepted';
        $friend->save();
        
        // Créer une notification pour l'expéditeur
        Notification::create([
            'user_id' => $friend->user_id, // Destinataire (celui qui a envoyé la demande)
            'from_user_id' => Auth::id(), // Expéditeur (celui qui accepte la demande)
            'type' => 'friend_accepted',
            'notifiable_id' => $friend->id,
            'notifiable_type' => Friend::class,
            'content' => Auth::user()->name . ' a accepté votre demande d\'ami',
        ]);
        
        return redirect()->back()->with('success', 'Demande d\'ami acceptée !');
    }
    
    /**
     * Rejeter une demande d'ami
     */
    public function rejectRequest(Friend $friend)
    {
        // Vérifier que l'utilisateur est bien le destinataire de la demande d'ami
        if ($friend->friend_id !== Auth::id()) {
            abort(403, 'Non autorisé');
        }
        
        // Mettre à jour le statut de la demande d'ami
        $friend->status = 'rejected';
        $friend->save();
        
        return redirect()->back()->with('success', 'Demande d\'ami rejetée !');
    }
    
    /**
     * Supprimer un ami
     */
    public function removeFriend(Friend $friend)
    {
        // Vérifier que l'utilisateur est bien concerné par cette relation d'amitié
        if ($friend->user_id !== Auth::id() && $friend->friend_id !== Auth::id()) {
            abort(403, 'Non autorisé');
        }
        
        // Supprimer la relation d'amitié
        $friend->delete();
        
        return redirect()->back()->with('success', 'Ami supprimé !');
    }
    
    /**
     * Suggestions d'amis pour l'utilisateur
     */
    public function suggestions(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');
        
        // Récupérer les IDs des amis et des demandes en cours
        $friendIds = $this->getFriendAndRequestIds($user->id);
        
        // Suggérer des utilisateurs qui ne sont pas encore amis
        $suggestions = User::whereNotIn('id', $friendIds);
        
        // Filtrer par recherche si présente
        if ($search) {
            $suggestions = $suggestions->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Récupérer les résultats
        $suggestions = $suggestions->orderBy('name')
            ->paginate(12)
            ->withQueryString();
        
        // Récupérer des utilisateurs populaires ou au hasard si aucun résultat avec la recherche
        $randomSuggestions = collect();
        if ($suggestions->isEmpty() && !$search) {
            $randomSuggestions = User::whereNotIn('id', $friendIds)
                ->inRandomOrder()
                ->limit(8)
                ->get();
        }
        
        return view('friends.suggestions', compact('suggestions', 'randomSuggestions', 'search'));
    }
    
    /**
     * Rechercher des utilisateurs à ajouter comme amis
     */
    public function search(Request $request)
    {
        $search = $request->input('search');
        $user = Auth::user();
        
        // Récupérer les IDs des amis et des demandes en cours
        $friendIds = $this->getFriendAndRequestIds($user->id);
        
        // Si la recherche est vide, rediriger vers les suggestions
        if (!$search) {
            return redirect()->route('friends.suggestions');
        }
        
        // Rechercher des utilisateurs par nom ou email
        $users = User::where('id', '!=', $user->id)
            ->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();
        
        // Pour chaque utilisateur, déterminer la relation d'amitié
        foreach ($users as $potentialFriend) {
            $friendship = Friend::where(function($query) use ($user, $potentialFriend) {
                    $query->where('user_id', $user->id)->where('friend_id', $potentialFriend->id);
                })
                ->orWhere(function($query) use ($user, $potentialFriend) {
                    $query->where('user_id', $potentialFriend->id)->where('friend_id', $user->id);
                })
                ->first();
            
            $potentialFriend->friendship = $friendship;
        }
        
        return view('friends.search', compact('users', 'search'));
    }
    
    /**
     * Helper pour récupérer les IDs des amis et des demandes en cours
     */
    private function getFriendAndRequestIds($userId)
    {
        // Récupérer les IDs des amis (acceptés)
        $sentFriendIds = Friend::where('user_id', $userId)
            ->whereIn('status', ['accepted', 'pending'])
            ->pluck('friend_id')
            ->toArray();
            
        $receivedFriendIds = Friend::where('friend_id', $userId)
            ->whereIn('status', ['accepted', 'pending'])
            ->pluck('user_id')
            ->toArray();
            
        $friendIds = array_merge($sentFriendIds, $receivedFriendIds);
        $friendIds[] = $userId; // Exclure l'utilisateur lui-même
        
        return $friendIds;
    }
}
