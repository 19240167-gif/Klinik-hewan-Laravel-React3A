<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PemilikHewanController;
use App\Http\Controllers\Api\HewanController;
use App\Http\Controllers\Api\ObatController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Auth (public)
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Users
    Route::apiResource('users', UserController::class);

    // Pemilik Hewan
    Route::apiResource('pemilik-hewan', PemilikHewanController::class);

    // Hewan
    Route::apiResource('hewan', HewanController::class);
    Route::get('/hewan-by-pemilik/{id_pemilik}', [HewanController::class, 'getByPemilik']);

    // Obat
    Route::apiResource('obat', ObatController::class);
});
