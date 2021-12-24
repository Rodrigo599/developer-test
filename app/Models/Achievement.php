<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    /**
     * Get milestones related to achievement completion
     */
    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }

    /**
     * Get achievement completion for users
     */
    public function achievements()
    {
        return $this->hasMany(Achievement::class);
    }
}
