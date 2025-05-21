<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Routes publiques
Route::get('/', function () {
    return view('welcome');
});

// Routes protégées par l'authentification
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard (fil d'actualité)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
    
    // Publications
    Route::resource('posts', PostController::class);
    
    // Commentaires
    Route::resource('comments', CommentController::class)->except(['index', 'show']);
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('posts.comments.store');
    Route::post('/comments/{comment}/reply', [CommentController::class, 'reply'])->name('comments.reply');
    
    // Likes
    Route::post('/posts/{post}/like', [LikeController::class, 'likePost'])->name('posts.like');
    Route::post('/comments/{comment}/like', [LikeController::class, 'likeComment'])->name('comments.like');
    Route::delete('/posts/{post}/unlike', [LikeController::class, 'unlikePost'])->name('posts.unlike');
    Route::delete('/comments/{comment}/unlike', [LikeController::class, 'unlikeComment'])->name('comments.unlike');
    
    // Amis
    Route::get('/friends', [FriendController::class, 'index'])->name('friends.index');
    Route::get('/friends/suggestions', [FriendController::class, 'suggestions'])->name('friends.suggestions');
    Route::post('/friends/{user}', [FriendController::class, 'sendRequest'])->name('friends.request');
    Route::patch('/friends/{friend}/accept', [FriendController::class, 'acceptRequest'])->name('friends.accept');
    Route::patch('/friends/{friend}/reject', [FriendController::class, 'rejectRequest'])->name('friends.reject');
    Route::delete('/friends/{friend}', [FriendController::class, 'removeFriend'])->name('friends.remove');
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read.all');
    
    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [MessageController::class, 'send'])->name('messages.send');
    Route::patch('/messages/{message}/read', [MessageController::class, 'markAsRead'])->name('messages.read');
});

require __DIR__.'/auth.php';
