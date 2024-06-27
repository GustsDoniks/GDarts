<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function create()
    {
        return view('games.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'player2' => 'required|string|max:255',
            'game_type' => 'required|string',
            'legs' => 'nullable|integer',
            'set_type' => 'required|string',
            'legs_in_set' => 'nullable|integer',
            'target_score' => 'required|integer',
        ]);

        $game = Game::create([
            'player1_id' => Auth::id(),
            'player2_id' => User::where('name', $validatedData['player2'])->first()->id ?? null,
            'score1' => 0,
            'score2' => 0,
            'player1_180s' => 0,
            'player2_180s' => 0,
            'player1_checkout_percentage' => 0.00,
            'player2_checkout_percentage' => 0.00,
            'status' => 'pending',
            'tournament_id' => $request->tournament_id,
        ]);

        return redirect()->route('games.show', $game->id);
    }

    public function show(Game $game)
    {
        return view('games.show', compact('game'));
    }
}
