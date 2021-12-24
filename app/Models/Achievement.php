<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    const MODELS_ARRAY = ['Comment', 'Lesson'];

    protected $fillable = [
        'name',
        'model',
        'milestone',
    ];

    /**
     * Get achievement completion for users
     */
    public function achievements()
    {
        return $this->hasMany(Achievement::class);
    }
}
