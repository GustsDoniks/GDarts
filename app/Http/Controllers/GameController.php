<?php
namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function create(Request $request)
    {
        $opponent = null;
        if ($request->has('opponent_id')) {
            $opponent = User::find($request->input('opponent_id'));
        }
        $tournament_id = $request->input('tournament_id', null);
        return view('games.create', compact('opponent', 'tournament_id'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'player2' => 'required|string|max:255',
            'game_type' => 'required|string',
            'legs' => 'nullable|integer',
            'set_type' => 'required|string',
            'legs_in_set' => 'nullable|integer',
            'sets' => 'nullable|integer',
            'target_score' => 'required|integer',
        ]);

        $player2 = User::where('name', $validatedData['player2'])->first();

        if (!$player2) {
            return redirect()->back()->withErrors(['player2' => 'Player 2 not found.']);
        }

        $game = Game::create([
            'player1_id' => Auth::id(),
            'player2_id' => $player2->id,
            'score1' => 0,
            'score2' => 0,
            'player1_180s' => 0,
            'player2_180s' => 0,
            'player1_checkout_percentage' => 0.00,
            'player2_checkout_percentage' => 0.00,
            'status' => 'pending',
            'game_type' => $validatedData['game_type'],
            'legs' => $validatedData['legs'] ?? null,
            'set_type' => $validatedData['set_type'],
            'legs_in_set' => $validatedData['legs_in_set'] ?? null,
            'sets' => $validatedData['sets'] ?? null,
            'target_score' => $validatedData['target_score'],
        ]);

        return redirect()->route('games.play', $game->id);
    }

    public function play(Game $game)
    {
        return view('games.play', compact('game'));
    }

    public function updateScore(Request $request, Game $game)
    {
        $data = $request->validate([
            'player' => 'required|string',
            'score' => 'required|integer',
        ]);

        $player = $data['player'];

        if ($player === 'player1') {
            $game->score1 += $data['score'];
            $game->player1_180s += ($data['score'] == 180 ? 1 : 0);
            $game->save();

            $stats = [
                'average' => $this->calculateAverage($game->score1),
                'checkout_percentage' => $this->calculateCheckoutPercentage($game->player1_checkout_percentage, $data['score']),
                'one_eighties' => $game->player1_180s,
                'leg_score' => $game->score1,
                'sets' => $game->sets,
                'legs' => $game->legs,
            ];
        } else {
            $game->score2 += $data['score'];
            $game->player2_180s += ($data['score'] == 180 ? 1 : 0);
            $game->save();

            $stats = [
                'average' => $this->calculateAverage($game->score2),
                'checkout_percentage' => $this->calculateCheckoutPercentage($game->player2_checkout_percentage, $data['score']),
                'one_eighties' => $game->player2_180s,
                'leg_score' => $game->score2,
                'sets' => $game->sets,
                'legs' => $game->legs,
            ];
        }

        return response()->json(['success' => true, 'stats' => $stats]);
    }

    private function calculateAverage($score)
    {
        return $score / 3; // Example calculation (placeholder)
    }

    private function calculateCheckoutPercentage($currentPercentage, $score)
    {
        return $currentPercentage; // Placeholder logic
    }

    public function submitGame(Game $game)
    {
        $game->status = 'completed';
        $game->save();

        return response()->json(['success' => true, 'message' => 'Game submitted successfully']);
    }

    public function destroy(Game $game)
    {
        if (!Auth::user()->is_admin) {
            return redirect()->route('home')->with('error', 'You do not have permission to delete this game.');
        }
    
        $game->delete();
        return redirect()->route('home')->with('success', 'Game deleted successfully.');
    }

}
