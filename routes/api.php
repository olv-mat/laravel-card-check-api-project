<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    UserController,
    CardController,
    BINController
};

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/user', [UserController::class, 'user']);
    Route::post('/card/check', [CardController::class, 'check']);
    Route::get('/card/generate', [CardController::class, 'generate']);
    Route::post('/bin/check', [BINController::class, 'check']);
});