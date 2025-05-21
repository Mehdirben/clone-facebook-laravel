<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Notification;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * Aimer une publication
     */
    public function likePost(Request $request, Post $post)
    {
        $request->validate([
            'type' => 'required|in:like,love,haha,wow,sad,angry',
        ]);
        
        // Vérifier si l'utilisateur a déjà aimé le post
        $existingLike = Like::where('user_id', Auth::id())
            ->where('likeable_id', $post->id)
            ->where('likeable_type', Post::class)
            ->first();
            
        if ($existingLike) {
            // Si le type de like est différent, mettre à jour
            if ($existingLike->type !== $request->type) {
                $existingLike->type = $request->type;
                $existingLike->save();
                return redirect()->back()->with('success', 'Réaction mise à jour !');
            }
            
            return redirect()->back()->with('error', 'Vous avez déjà aimé cette publication.');
        }
        
        // Créer un nouveau like
        $like = new Like();
        $like->user_id = Auth::id();
        $like->likeable_id = $post->id;
        $like->likeable_type = Post::class;
        $like->type = $request->type;
        $like->save();
        
        // Créer une notification pour le propriétaire du post
        if ($post->user_id != Auth::id()) {
            Notification::create([
                'user_id' => $post->user_id, // Destinataire
                'from_user_id' => Auth::id(), // Expéditeur
                'type' => 'like_post',
                'notifiable_id' => $like->id,
                'notifiable_type' => Like::class,
                'content' => Auth::user()->name . ' a réagi à votre publication avec un "' . $like->type . '"',
            ]);
        }
        
        return redirect()->back()->with('success', 'Publication aimée !');
    }
    
    /**
     * Ne plus aimer une publication
     */
    public function unlikePost(Post $post)
    {
        // Supprimer le like
        $like = Like::where('user_id', Auth::id())
            ->where('likeable_id', $post->id)
            ->where('likeable_type', Post::class)
            ->first();
            
        if (!$like) {
            return redirect()->back()->with('error', "Vous n'avez pas aimé cette publication.");
        }
        
        // Supprimer la notification associée
        Notification::where('notifiable_id', $like->id)
            ->where('notifiable_type', Like::class)
            ->delete();
            
        $like->delete();
        
        return redirect()->back()->with('success', 'Vous n\'aimez plus cette publication.');
    }
    
    /**
     * Aimer un commentaire
     */
    public function likeComment(Request $request, Comment $comment)
    {
        $request->validate([
            'type' => 'required|in:like,love,haha,wow,sad,angry',
        ]);
        
        // Vérifier si l'utilisateur a déjà aimé le commentaire
        $existingLike = Like::where('user_id', Auth::id())
            ->where('likeable_id', $comment->id)
            ->where('likeable_type', Comment::class)
            ->first();
            
        if ($existingLike) {
            // Si le type de like est différent, mettre à jour
            if ($existingLike->type !== $request->type) {
                $existingLike->type = $request->type;
                $existingLike->save();
                return redirect()->back()->with('success', 'Réaction mise à jour !');
            }
            
            return redirect()->back()->with('error', 'Vous avez déjà aimé ce commentaire.');
        }
        
        // Créer un nouveau like
        $like = new Like();
        $like->user_id = Auth::id();
        $like->likeable_id = $comment->id;
        $like->likeable_type = Comment::class;
        $like->type = $request->type;
        $like->save();
        
        // Créer une notification pour le propriétaire du commentaire
        if ($comment->user_id != Auth::id()) {
            Notification::create([
                'user_id' => $comment->user_id, // Destinataire
                'from_user_id' => Auth::id(), // Expéditeur
                'type' => 'like_comment',
                'notifiable_id' => $like->id,
                'notifiable_type' => Like::class,
                'content' => Auth::user()->name . ' a réagi à votre commentaire avec un "' . $like->type . '"',
            ]);
        }
        
        return redirect()->back()->with('success', 'Commentaire aimé !');
    }
    
    /**
     * Ne plus aimer un commentaire
     */
    public function unlikeComment(Comment $comment)
    {
        // Supprimer le like
        $like = Like::where('user_id', Auth::id())
            ->where('likeable_id', $comment->id)
            ->where('likeable_type', Comment::class)
            ->first();
            
        if (!$like) {
            return redirect()->back()->with('error', "Vous n'avez pas aimé ce commentaire.");
        }
        
        // Supprimer la notification associée
        Notification::where('notifiable_id', $like->id)
            ->where('notifiable_type', Like::class)
            ->delete();
            
        $like->delete();
        
        return redirect()->back()->with('success', 'Vous n\'aimez plus ce commentaire.');
    }
}
