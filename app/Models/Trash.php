<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trash extends Model
{
    protected $fillable = [
        'name',
        'points',
        'image'
    ];

    public function actions()
    {
        return $this->hasMany(Action::class);
    }
}
