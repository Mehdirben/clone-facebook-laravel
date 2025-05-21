<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::where('user_id', Auth::id())
            ->with(['user', 'comments.user', 'likes'])
            ->latest()
            ->paginate(10);
            
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|mimes:mp4,mov,avi|max:20480',
            'is_public' => 'boolean',
        ]);
        
        $post = new Post();
        $post->user_id = Auth::id();
        $post->content = $request->content;
        $post->is_public = $request->has('is_public');
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts/images', 'public');
            $post->image = $imagePath;
        }
        
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('posts/videos', 'public');
            $post->video = $videoPath;
        }
        
        $post->save();
        
        return redirect()->route('dashboard')->with('success', 'Publication créée avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        // Vérifie si l'utilisateur peut voir cette publication
        if (!$post->is_public && $post->user_id !== Auth::id()) {
            abort(403, "Vous n'êtes pas autorisé à voir cette publication.");
        }
        
        $post->load(['user', 'comments.user', 'likes']);
        
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        // Vérifie si l'utilisateur est autorisé à modifier cette publication
        if ($post->user_id !== Auth::id()) {
            abort(403, "Vous n'êtes pas autorisé à modifier cette publication.");
        }
        
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // Vérifie si l'utilisateur est autorisé à modifier cette publication
        if ($post->user_id !== Auth::id()) {
            abort(403, "Vous n'êtes pas autorisé à modifier cette publication.");
        }
        
        $request->validate([
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|mimes:mp4,mov,avi|max:20480',
            'is_public' => 'boolean',
        ]);
        
        $post->content = $request->content;
        $post->is_public = $request->has('is_public');
        
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            
            $imagePath = $request->file('image')->store('posts/images', 'public');
            $post->image = $imagePath;
        }
        
        if ($request->hasFile('video')) {
            // Supprimer l'ancienne vidéo si elle existe
            if ($post->video) {
                Storage::disk('public')->delete($post->video);
            }
            
            $videoPath = $request->file('video')->store('posts/videos', 'public');
            $post->video = $videoPath;
        }
        
        $post->save();
        
        return redirect()->route('posts.show', $post)->with('success', 'Publication mise à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Vérifie si l'utilisateur est autorisé à supprimer cette publication
        if ($post->user_id !== Auth::id()) {
            abort(403, "Vous n'êtes pas autorisé à supprimer cette publication.");
        }
        
        // Supprimer les fichiers associés
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }
        
        if ($post->video) {
            Storage::disk('public')->delete($post->video);
        }
        
        $post->delete();
        
        return redirect()->route('dashboard')->with('success', 'Publication supprimée avec succès !');
    }
}
