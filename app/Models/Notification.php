<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'from_user_id',
        'type',
        'notifiable_id',
        'notifiable_type',
        'content',
        'is_read',
    ];
    
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
        ];
    }
    
    /**
     * Relation avec l'utilisateur qui reçoit la notification
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Relation avec l'utilisateur qui a causé la notification
     */
    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }
    
    /**
     * Relation polymorphique avec l'élément qui a causé la notification
     */
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }
}
