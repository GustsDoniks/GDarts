<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public function tournaments()
    {
        return $this->belongsToMany(Tournament::class, 'tournament_players', 'user_id', 'tournament_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function gamesAsPlayer1()
    {
        return $this->hasMany(Game::class, 'player1_id');
    }
    
    public function gamesAsPlayer2()
    {
        return $this->hasMany(Game::class, 'player2_id');
    }
    
    public function getAllGamesAttribute()
    {
        $gamesAsPlayer1 = $this->gamesAsPlayer1;
        $gamesAsPlayer2 = $this->gamesAsPlayer2;
    
        return $gamesAsPlayer1->merge($gamesAsPlayer2);
    }

    public function tournamentsAsPlayer()
    {
        return $this->belongsToMany(Tournament::class, 'tournament_players', 'user_id', 'tournament_id');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
    ];
}
