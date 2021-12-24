<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    use HasFactory;

    const MODELS_ARRAY = ['Comment', 'Lesson'];

    protected $fillable = [
        'achievement_id',
        'model',
        'milestone',
    ];

    /**
     * Get achievement related to milestone
     */
    public function achievement()
    {
        return $this->belongsTo(Achievement::class);
    }
}
