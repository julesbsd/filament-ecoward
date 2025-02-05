<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory;

    // Les champs qui peuvent être remplis via un formulaire
    protected $fillable = [
        'name',
        'description',
        'base_goal',
        'increment_goal',
        'trash_id',
        'type_id',
        'points',
        'is_active',
    ];

    /**
     * Relation: un défi peut être activé par plusieurs utilisateurs.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_challenges')
                    ->withPivot('current_level', 'progress')
                    ->withTimestamps();
    }

    /**
     * Scope pour récupérer les défis actifs.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Relation: un défi est associé à un type de défi.
     */
    public function type()
    {
        return $this->belongsTo(ChallengeType::class);
    }

    /**
     * Relation: un défi est associé à un type de déchet.
     */
    public function trash()
    {
        return $this->belongsTo(Trash::class);
    }
}
