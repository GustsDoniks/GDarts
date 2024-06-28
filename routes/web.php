<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TournamentController;


Route::get('/', function () {
    return redirect()->route('home');
});
Route::get('/index', [HomeController::class, 'index'])->name('home');

Route::get('/games/create', [GameController::class, 'create'])->name('games.create');
Route::post('/games', [GameController::class, 'store'])->name('games.store');
Route::post('/games/start', [GameController::class, 'start'])->name('games.start');
Route::get('/games/play/{game}', [GameController::class, 'play'])->name('games.play');
Route::post('/games/{game}/updateScore', [GameController::class, 'updateScore'])->name('games.updateScore');
Route::post('/games/{game}/submit', [GameController::class, 'submitGame'])->name('games.submit');

Route::middleware(['auth'])->group(function () {
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::delete('/tournaments/{tournament}', [TournamentController::class, 'destroy'])->name('tournaments.destroy');
    Route::delete('/games/{game}', [GameController::class, 'destroy'])->name('games.destroy');
});



Route::get('/tournaments/create', [TournamentController::class, 'create'])->name('tournaments.create');
Route::post('/tournaments', [TournamentController::class, 'store'])->name('tournaments.store');
Route::get('/tournaments/{tournament}', [TournamentController::class, 'show'])->name('tournaments.show');
Route::post('/tournaments/{tournament}/comments', [CommentController::class, 'store'])->name('tournaments.comments.store')->middleware('auth');

Route::post('/tournaments/{tournament}/add-player/{user}', [TournamentController::class, 'addPlayer'])
    ->name('tournaments.add-player')
    ->middleware('auth');

Route::delete('/tournaments/{tournament}/remove-player/{user}', [TournamentController::class, 'removePlayer'])
    ->name('tournaments.remove-player')
    ->middleware('auth');

Route::post('/tournaments/{tournament}/start-game', [TournamentController::class, 'startGame'])
    ->name('tournaments.start-game')
    ->middleware('auth');    

Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
    ->middleware('admin')
    ->name('comments.destroy');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/admin-test', function () {
    return 'Admin middleware is working!';
})->middleware('admin');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/search-users', [UserController::class, 'search'])->name('search-users');

// Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
// Route::post('register', [RegisterController::class, 'register']);

require __DIR__.'/auth.php';
