<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    use HasFactory;


    protected $fillable = [
        'action_type_id',
        'challenge_id',
        'status',
        'trash_id',
        'user_id',
        'quantity',
        'image_action',
        'image_throw',
        'location',
    ];

    public function actionType()
    {
        return $this->belongsTo(ActionType::class, 'action_type_id');
    }

    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    public function trash()
    {
        return $this->belongsTo(Trash::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
