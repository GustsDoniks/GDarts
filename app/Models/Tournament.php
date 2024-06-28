<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    protected $fillable = [
        'name',
        'location',
        'website',
        'start_date',
        'end_date',
        'description',
        'creator_id',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function players()
    {
        return $this->belongsToMany(User::class, 'tournament_players', 'tournament_id', 'user_id');
    }

    public function games()
    {
        return $this->hasMany(Game::class);
    }
    
    use HasFactory;
}
