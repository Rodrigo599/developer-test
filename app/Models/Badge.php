<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'achievement_amount'
    ];

    /**
     * Get badge completion for users
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
