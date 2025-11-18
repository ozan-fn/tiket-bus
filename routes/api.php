<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// =================== AUTH & USER ===================
Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
Route::get('/auth/google', [App\Http\Controllers\Api\GoogleAuthController::class, 'redirect']);
Route::get('/auth/google/callback', [App\Http\Controllers\Api\GoogleAuthController::class, 'callback']);

// =================== PUBLIC ENDPOINTS ===================
Route::get('jadwal-bus', [App\Http\Controllers\Api\JadwalBusController::class, 'index']);
Route::get('jadwal-bus/{id}', [App\Http\Controllers\Api\JadwalBusController::class, 'show']);
Route::get('data-bus', [App\Http\Controllers\Api\DataBusController::class, 'index']);
Route::get('data-bus/{id}', [App\Http\Controllers\Api\DataBusController::class, 'show']);
Route::get('terminal-rute', [App\Http\Controllers\Api\TerminalRuteController::class, 'index']);
Route::get('rute-terminals/{id}', [App\Http\Controllers\Api\TerminalRuteController::class, 'ruteTerminals']);

// =================== USER ENDPOINTS ===================
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('pemesanan-tiket', [App\Http\Controllers\Api\PemesananTiketController::class, 'store']);
    Route::post('pembayaran-tiket', [App\Http\Controllers\Api\PembayaranTiketController::class, 'store']);
    Route::get('/user', [App\Http\Controllers\Api\ProfileController::class, 'show']);
    Route::post('/user/update', [App\Http\Controllers\Api\ProfileController::class, 'update']);
    Route::post('/user/update-password', [App\Http\Controllers\Api\ProfileController::class, 'updatePassword']);
});

// =================== ADMIN/OPERATOR ENDPOINTS ===================
Route::middleware(['auth:sanctum', 'role:admin|operator'])->group(function () {
    // CRUD Master Data
    Route::apiResource('bus', App\Http\Controllers\Api\BusController::class);
    Route::apiResource('fasilitas', App\Http\Controllers\Api\FasilitasController::class);
    Route::apiResource('bus-fasilitas', App\Http\Controllers\Api\BusFasilitasController::class);
    Route::apiResource('rute', App\Http\Controllers\Api\RuteController::class);
    Route::apiResource('terminal', App\Http\Controllers\Api\TerminalController::class);
    Route::apiResource('sopir', App\Http\Controllers\Api\SopirController::class);
    Route::apiResource('kelas-bus', App\Http\Controllers\Api\KelasBusController::class);
    Route::apiResource('jadwal-kelas-bus', App\Http\Controllers\Api\JadwalKelasBusController::class);
    // Buat jadwal bus
    Route::post('jadwal-bus', [App\Http\Controllers\Api\JadwalBusController::class, 'store'])->middleware('role:admin');
});
