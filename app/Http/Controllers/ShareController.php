<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Post;
use App\Models\Share;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShareController extends Controller
{
    /**
     * Partager un post
     */
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'comment' => 'nullable|string',
        ]);
        
        // Vérifier si le post est public ou appartient à l'utilisateur
        if (!$post->is_public && $post->user_id !== Auth::id()) {
            abort(403, "Vous ne pouvez pas partager cette publication privée.");
        }
        
        // Vérifier si l'utilisateur a déjà partagé ce post
        $existingShare = Share::where('user_id', Auth::id())
            ->where('post_id', $post->id)
            ->first();
            
        if ($existingShare) {
            return redirect()->back()->with('error', 'Vous avez déjà partagé cette publication.');
        }
        
        $share = new Share();
        $share->user_id = Auth::id();
        $share->post_id = $post->id;
        $share->comment = $request->comment;
        $share->save();
        
        // Créer une notification pour le propriétaire du post
        if ($post->user_id != Auth::id()) {
            Notification::create([
                'user_id' => $post->user_id, // Destinataire
                'from_user_id' => Auth::id(), // Expéditeur
                'type' => 'share',
                'notifiable_id' => $share->id,
                'notifiable_type' => Share::class,
                'content' => Auth::user()->name . ' a partagé votre publication',
            ]);
        }
        
        return redirect()->back()->with('success', 'Publication partagée avec succès !');
    }
    
    /**
     * Mettre à jour un partage
     */
    public function update(Request $request, Share $share)
    {
        // Vérifier si l'utilisateur est autorisé à modifier ce partage
        if ($share->user_id !== Auth::id()) {
            abort(403, "Vous n'êtes pas autorisé à modifier ce partage.");
        }
        
        $request->validate([
            'comment' => 'nullable|string',
        ]);
        
        $share->comment = $request->comment;
        $share->save();
        
        return redirect()->back()->with('success', 'Partage mis à jour avec succès !');
    }
    
    /**
     * Supprimer un partage
     */
    public function destroy(Share $share)
    {
        // Vérifier si l'utilisateur est autorisé à supprimer ce partage
        if ($share->user_id !== Auth::id()) {
            abort(403, "Vous n'êtes pas autorisé à supprimer ce partage.");
        }
        
        // Supprimer toutes les notifications associées
        Notification::where('notifiable_id', $share->id)
            ->where('notifiable_type', Share::class)
            ->delete();
        
        $share->delete();
        
        return redirect()->back()->with('success', 'Partage supprimé avec succès !');
    }
} 