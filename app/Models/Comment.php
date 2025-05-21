<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Comment extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'post_id',
        'parent_id',
        'content',
        'image',
    ];
    
    /**
     * Relation avec l'utilisateur qui a commenté
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Relation avec le post commenté
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
    
    /**
     * Relation avec le commentaire parent (pour les réponses)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
    
    /**
     * Relation avec les réponses à ce commentaire
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
    
    /**
     * Relation avec les likes
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }
    
    /**
     * Méthode pour vérifier si un commentaire est aimé par un utilisateur
     */
    public function isLikedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }
    
    /**
     * Obtenir le nombre de likes
     */
    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }
}
