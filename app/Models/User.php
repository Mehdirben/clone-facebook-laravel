<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    /**
     * Relation avec le profil
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }
    
    /**
     * Relation avec les posts
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
    
    /**
     * Relation avec les commentaires
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
    
    /**
     * Relation avec les likes
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }
    
    /**
     * Relation avec les partages
     */
    public function shares(): HasMany
    {
        return $this->hasMany(Share::class);
    }
    
    /**
     * Demandes d'amis envoyées
     */
    public function sentFriendRequests(): HasMany
    {
        return $this->hasMany(Friend::class, 'user_id');
    }
    
    /**
     * Demandes d'amis reçues
     */
    public function receivedFriendRequests(): HasMany
    {
        return $this->hasMany(Friend::class, 'friend_id');
    }
    
    /**
     * Amis acceptés
     */
    public function friends()
    {
        return $this->sentFriendRequests()
            ->where('status', 'accepted')
            ->with('friend')
            ->get()
            ->pluck('friend')
            ->merge(
                $this->receivedFriendRequests()
                    ->where('status', 'accepted')
                    ->with('user')
                    ->get()
                    ->pluck('user')
            );
    }
    
    /**
     * Notifications reçues
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }
    
    /**
     * Messages envoyés
     */
    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'user_id');
    }
    
    /**
     * Messages reçus
     */
    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }
}
