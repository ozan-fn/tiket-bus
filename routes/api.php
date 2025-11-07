<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/auth/google', [App\Http\Controllers\GoogleAuthController::class, 'redirect']);
Route::get('/auth/google/callback', [App\Http\Controllers\GoogleAuthController::class, 'callback']);

Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
