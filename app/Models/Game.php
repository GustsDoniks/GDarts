<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'player1_id',
        'player2_id',
        'score1',
        'score2',
        'player1_180s',
        'player2_180s',
        'player1_checkout_percentage',
        'player2_checkout_percentage',
        'status',
        'tournament_id',
    ];

    public function player1()
    {
        return $this->belongsTo(User::class, 'player1_id');
    }

    public function player2()
    {
        return $this->belongsTo(User::class, 'player2_id');
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }
}
