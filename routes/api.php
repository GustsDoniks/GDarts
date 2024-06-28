<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\GameController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/games', [GameController::class, 'index']);


Route::middleware('auth:sanctum')->delete('/games/{game}', [GameController::class, 'destroy']);


