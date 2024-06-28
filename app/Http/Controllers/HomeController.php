<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\Game;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // public function __construct()
    // {
    //     // Apply middleware to all methods except 'index'
    //     $this->middleware('auth')->except('index');
    // }

    public function index()
    {
        $tournaments = Tournament::orderBy('start_date', 'desc')->get();

        $recentGames = Game::with(['player1', 'player2'])->orderBy('created_at', 'desc')->limit(10)->get();

        return view('index', compact('tournaments', 'recentGames'));
    }
}
