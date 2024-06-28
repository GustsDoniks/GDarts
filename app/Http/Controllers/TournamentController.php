<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tournament;
use App\Models\Game;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TournamentController extends Controller
{
    public function create()
    {
        return view('tournaments.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'website' => 'nullable|url|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
        ]);

        $validatedData['creator_id'] = Auth::id();

        $tournament = Tournament::create($validatedData);

        return redirect()->route('tournaments.show', $tournament->id)
            ->with('success', 'Tournament created successfully!');
    }

    public function show(Request $request, Tournament $tournament)
    {
        $players = null;
        if ($request->has('search') && auth()->id() == $tournament->creator_id) {
            $players = User::where('name', 'like', '%' . $request->search . '%')->get();
        }

        $completedGames = $tournament->games()->where('status', 'completed')->get();

        return view('tournaments.show', compact('tournament', 'players', 'completedGames'));
    }
    

    public function addPlayer(Request $request, Tournament $tournament, User $user)
    {
        $this->authorize('update', $tournament);
    
        $tournament->players()->attach($user);
    
        return back()->with('success', 'Player added successfully to the tournament.');
    }

    public function removePlayer(Request $request, Tournament $tournament, User $user)
    {
        $this->authorize('update', $tournament);

        // Detach the user from the tournament
        $tournament->players()->detach($user);

        return back()->with('success', 'Player removed successfully from the tournament.');
    }

    public function destroy(Tournament $tournament)
    {
        if (!Auth::user()->is_admin) {
            return redirect()->route('home')->with('error', 'You do not have permission to delete this tournament.');
        }
    
        $tournament->delete();
        return redirect()->route('home')->with('success', 'Tournament deleted successfully.');
    }

    public function startGame(Request $request, Tournament $tournament)
    {
        $request->validate([
            'opponent_id' => 'required|exists:users,id',
            'game_type' => 'required|string',
            'legs' => 'nullable|integer',
            'sets' => 'nullable|integer',
            'target_score' => 'required|integer',
            'set_type' => 'nullable|string'
        ]);

        $game = Game::create([
            'player1_id' => Auth::id(),
            'player2_id' => $request->opponent_id,
            'tournament_id' => $tournament->id,
            'game_type' => $request->game_type,
            'legs' => $request->legs,
            'sets' => $request->sets,
            'target_score' => $request->target_score,
            'score1' => 0,
            'score2' => 0,
            'player1_180s' => 0,
            'player2_180s' => 0,
            'player1_checkout_percentage' => 0.00,
            'player2_checkout_percentage' => 0.00,
            'status' => 'pending',
        ]);

        return redirect()->route('games.play', $game->id)->with('success', 'Game started successfully!');
    }

    

    // public function searchPlayers(Request $request, Tournament $tournament)
    // {
    //     $this->authorize('update', $tournament);

    //     $players = User::where('name', 'like', '%' . $request->search . '%')->get();

    //     return view('tournaments.search-players', compact('tournament', 'players'));
    // }
}
