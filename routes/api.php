<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataBusController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\JadwalBusController;
use App\Http\Controllers\JadwalKelasBusController;
use App\Http\Controllers\KelasBusController;
use App\Http\Controllers\PembayaranTiketController;
use App\Http\Controllers\PemesananTiketController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RuteController;
use App\Http\Controllers\SopirController;
use App\Http\Controllers\TerminalController;
use App\Http\Controllers\TerminalRuteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// =================== AUTH & USER ===================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/auth/google', [GoogleAuthController::class, 'redirect']);
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);

// =================== PUBLIC ENDPOINTS ===================
Route::get('jadwal-bus', [JadwalBusController::class, 'index']);
Route::get('jadwal-bus/{id}', [JadwalBusController::class, 'show']);
Route::get('data-bus', [DataBusController::class, 'index']);
Route::get('data-bus/{id}', [DataBusController::class, 'show']);
Route::get('terminal-rute', [TerminalRuteController::class, 'index']);
Route::get('rute-terminals/{id}', [TerminalRuteController::class, 'ruteTerminals']);

// =================== USER ENDPOINTS ===================
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('pemesanan-tiket', [PemesananTiketController::class, 'store']);
    Route::post('pembayaran-tiket', [PembayaranTiketController::class, 'store']);
    Route::get('/user', [ProfileController::class, 'show']);
    Route::post('/user/update', [ProfileController::class, 'update']);
    Route::post('/user/update-password', [ProfileController::class, 'updatePassword']);
});

// =================== ADMIN/OPERATOR ENDPOINTS ===================
Route::middleware(['auth:sanctum', 'role:admin|operator'])->group(function () {
    // CRUD Master Data
    Route::apiResource('fasilitas', FasilitasController::class);
    Route::apiResource('rute', RuteController::class);
    Route::apiResource('terminal', TerminalController::class);
    Route::apiResource('sopir', SopirController::class);
    Route::apiResource('kelas-bus', KelasBusController::class);
    Route::apiResource('jadwal-kelas-bus', JadwalKelasBusController::class);
    // Buat jadwal bus
    Route::post('jadwal-bus', [JadwalBusController::class, 'store'])->middleware('role:admin');
});
