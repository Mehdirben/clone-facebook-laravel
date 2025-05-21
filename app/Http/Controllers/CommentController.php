<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Notification;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $comment = new Comment();
        $comment->user_id = Auth::id();
        $comment->post_id = $post->id;
        $comment->content = $request->content;
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('comments/images', 'public');
            $comment->image = $imagePath;
        }
        
        $comment->save();
        
        // Créer une notification pour le propriétaire du post
        if ($post->user_id != Auth::id()) {
            Notification::create([
                'user_id' => $post->user_id, // Destinataire
                'from_user_id' => Auth::id(), // Expéditeur
                'type' => 'comment',
                'notifiable_id' => $comment->id,
                'notifiable_type' => Comment::class,
                'content' => Auth::user()->name . ' a commenté votre publication',
            ]);
        }
        
        return redirect()->back()->with('success', 'Commentaire ajouté avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        // Vérifie si l'utilisateur est autorisé à modifier ce commentaire
        if ($comment->user_id !== Auth::id()) {
            abort(403, "Vous n'êtes pas autorisé à modifier ce commentaire.");
        }
        
        $request->validate([
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $comment->content = $request->content;
        
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($comment->image) {
                Storage::disk('public')->delete($comment->image);
            }
            
            $imagePath = $request->file('image')->store('comments/images', 'public');
            $comment->image = $imagePath;
        }
        
        $comment->save();
        
        return redirect()->back()->with('success', 'Commentaire mis à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        // Vérifie si l'utilisateur est autorisé à supprimer ce commentaire
        if ($comment->user_id !== Auth::id()) {
            abort(403, "Vous n'êtes pas autorisé à supprimer ce commentaire.");
        }
        
        // Supprimer l'image associée
        if ($comment->image) {
            Storage::disk('public')->delete($comment->image);
        }
        
        // Supprimer toutes les notifications associées
        Notification::where('notifiable_id', $comment->id)
            ->where('notifiable_type', Comment::class)
            ->delete();
        
        $comment->delete();
        
        return redirect()->back()->with('success', 'Commentaire supprimé avec succès !');
    }

    /**
     * Ajouter une réponse à un commentaire
     */
    public function reply(Request $request, Comment $comment)
    {
        $request->validate([
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $reply = new Comment();
        $reply->user_id = Auth::id();
        $reply->post_id = $comment->post_id;
        $reply->parent_id = $comment->id;
        $reply->content = $request->content;
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('comments/images', 'public');
            $reply->image = $imagePath;
        }
        
        $reply->save();
        
        // Créer une notification pour le propriétaire du commentaire parent
        if ($comment->user_id != Auth::id()) {
            Notification::create([
                'user_id' => $comment->user_id, // Destinataire
                'from_user_id' => Auth::id(), // Expéditeur
                'type' => 'reply',
                'notifiable_id' => $reply->id,
                'notifiable_type' => Comment::class,
                'content' => Auth::user()->name . ' a répondu à votre commentaire',
            ]);
        }
        
        return redirect()->back()->with('success', 'Réponse ajoutée avec succès !');
    }
}
