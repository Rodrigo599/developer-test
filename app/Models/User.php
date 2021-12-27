<?php

namespace App\Models;

use App\Models\Comment;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The badges that belong to the user.
     */
    public function badges()
    {
        return $this->belongsToMany(Badge::class);
    }

    /**
     * The current badge of an user.
     */
    public function currentBadge()
    {
        return $this->badges->sortByDesc('achievement_amount')->first();
    }

    /**
     * The next badge of an user.
     */
    public function nextBadge()
    {
        return Badge::whereDoesntHave('users', function($query){
            $query->where('user_id', $this->id);
        })->orderBy('achievement_amount')->first();
    }

    /**
     * The comments that belong to the user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * The lessons that a user has access to.
     */
    public function lessons()
    {
        return $this->belongsToMany(Lesson::class);
    }
    
    /**
     * The achievements completed by the user
     */
    public function achievements()
    {
        return $this->belongsToMany(Achievement::class)->withTimestamps();
    }

    /**
     * The achievements available to the user
     */
    public function availableAchievements()
    {
        return Achievement::whereDoesntHave('users', function($query){
            $query->where('user_id', $this->id);
        })->orderBy('milestone')->get()->unique('model');
    }

    /**
     * The lessons that a user has watched.
     */
    public function watched()
    {
        return $this->belongsToMany(Lesson::class)->wherePivot('watched', true);
    }
}
