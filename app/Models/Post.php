<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Post extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'content',
        'image',
        'video',
        'is_public',
    ];
    
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
        ];
    }
    
    /**
     * Relation avec l'utilisateur qui a posté
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Relation avec les commentaires
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }
    
    /**
     * Relation avec les likes
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }
    
    /**
     * Relation avec les partages
     */
    public function shares(): HasMany
    {
        return $this->hasMany(Share::class);
    }
    
    /**
     * Méthode pour vérifier si un post est aimé par un utilisateur
     */
    public function isLikedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }
    
    /**
     * Méthode pour vérifier si un post est partagé par un utilisateur
     */
    public function isSharedBy(User $user)
    {
        return $this->shares()->where('user_id', $user->id)->exists();
    }
    
    /**
     * Obtenir le nombre de likes
     */
    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }
    
    /**
     * Obtenir le nombre de commentaires
     */
    public function getCommentsCountAttribute()
    {
        return $this->comments()->count();
    }
    
    /**
     * Obtenir le nombre de partages
     */
    public function getSharesCountAttribute()
    {
        return $this->shares()->count();
    }
}
