<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Mail;

Route::get('/', [GameController::class, 'index']);
Route::post('/play-game', [GameController::class, 'startGame'])->name('game.start');

