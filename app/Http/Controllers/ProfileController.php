<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Friend;
use App\Models\Post;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Afficher le profil d'un utilisateur
     */
    public function show(User $user): View
    {
        // Récupérer les publications publiques de l'utilisateur
        // (ou toutes les publications si l'utilisateur consulte son propre profil)
        $posts = Post::where('user_id', $user->id)
            ->when($user->id !== Auth::id(), function($query) {
                return $query->where('is_public', true);
            })
            ->with(['user', 'comments.user', 'likes', 'shares'])
            ->latest()
            ->paginate(10);
            
        // Récupérer les publications partagées par l'utilisateur
        $sharedPosts = $user->shares()
            ->with(['post.user', 'post.comments.user', 'post.likes'])
            ->latest()
            ->paginate(10);
            
        // Vérifier si l'utilisateur connecté est ami avec cet utilisateur
        $friendship = null;
        if (Auth::check() && $user->id !== Auth::id()) {
            $friendship = Friend::where(function($query) use ($user) {
                    $query->where('user_id', Auth::id())->where('friend_id', $user->id);
                })
                ->orWhere(function($query) use ($user) {
                    $query->where('user_id', $user->id)->where('friend_id', Auth::id());
                })
                ->first();
        }
        
        return view('profile.show', compact('user', 'posts', 'sharedPosts', 'friendship'));
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        
        // Récupérer ou créer le profil de l'utilisateur
        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);
        
        return view('profile.edit', compact('user', 'profile'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        // Mettre à jour les informations de l'utilisateur
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
        
        // Mettre à jour ou créer le profil
        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);
        
        $profile->fill($request->safe()->only([
            'bio',
            'location',
            'birthday',
            'phone',
            'website',
        ]));
        
        // Traiter la photo de profil
        if ($request->hasFile('profile_picture')) {
            // Supprimer l'ancienne photo si elle existe
            if ($profile->profile_picture) {
                Storage::disk('public')->delete($profile->profile_picture);
            }
            
            $profilePicturePath = $request->file('profile_picture')->store('profiles/pictures', 'public');
            $profile->profile_picture = $profilePicturePath;
        }
        
        // Traiter la photo de couverture
        if ($request->hasFile('cover_photo')) {
            // Supprimer l'ancienne photo si elle existe
            if ($profile->cover_photo) {
                Storage::disk('public')->delete($profile->cover_photo);
            }
            
            $coverPhotoPath = $request->file('cover_photo')->store('profiles/covers', 'public');
            $profile->cover_photo = $coverPhotoPath;
        }
        
        $user->profile()->save($profile);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        
        // Supprimer les fichiers associés au profil
        if ($user->profile) {
            if ($user->profile->profile_picture) {
                Storage::disk('public')->delete($user->profile->profile_picture);
            }
            
            if ($user->profile->cover_photo) {
                Storage::disk('public')->delete($user->profile->cover_photo);
            }
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
