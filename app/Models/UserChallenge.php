<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserChallenge extends Model
{
    use HasFactory;


    // Les champs qui peuvent être remplis via un formulaire ou via l'API
    protected $fillable = [
        'user_id',
        'challenge_id',
        'current_level',
        'progress',
    ];

    /**
     * Relation: un défi activé appartient à un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation: un défi activé est lié à un défi.
     */
    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    /**
     * Marquer le défi comme complété.
     */
    public function markAsCompleted()
    {
        $this->completed = true;
        $this->completed_at = now();
        $this->save();
    }
}
