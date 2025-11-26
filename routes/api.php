<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// =================== AUTH & USER ===================
Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
Route::get('/auth/google', [App\Http\Controllers\Api\GoogleAuthController::class, 'redirect']);
Route::get('/auth/google/callback', [App\Http\Controllers\Api\GoogleAuthController::class, 'callback']);

// =================== PUBLIC ENDPOINTS ===================
// Bus & Fasilitas (Public Read)
Route::get('bus', [App\Http\Controllers\Api\BusController::class, 'index']);
Route::get('bus/{id}', [App\Http\Controllers\Api\BusController::class, 'show']);
Route::get('fasilitas', [App\Http\Controllers\Api\FasilitasController::class, 'index']);

// Terminal & Rute
Route::get('terminal', [App\Http\Controllers\Api\TerminalController::class, 'index']);
Route::get('terminal/{id}', [App\Http\Controllers\Api\TerminalController::class, 'show']);
Route::get('rute', [App\Http\Controllers\Api\RuteController::class, 'index']);
Route::get('rute/{id}', [App\Http\Controllers\Api\RuteController::class, 'show']);

// Jadwal
Route::get('jadwal', [App\Http\Controllers\Api\JadwalController::class, 'index']);
Route::get('jadwal/{id}', [App\Http\Controllers\Api\JadwalController::class, 'show']);

// Kursi & Layout
Route::get('jadwal/{jadwal_id}/kursi', [App\Http\Controllers\Api\KursiController::class, 'getKursiByJadwal']);
Route::get('kursi/{kursi_id}/check', [App\Http\Controllers\Api\KursiController::class, 'checkKetersediaan']);

// Tiket Public (cek by kode)
Route::get('tiket/{kode_tiket}', [App\Http\Controllers\Api\TiketController::class, 'show']);

// Pembayaran Callback (dari payment gateway)
Route::post('pembayaran/callback', [App\Http\Controllers\Api\PembayaranController::class, 'callback']);

// =================== USER ENDPOINTS ===================
Route::middleware(['auth:sanctum'])->group(function () {
    // Auth
    Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);

    // Profile
    Route::get('/user', [App\Http\Controllers\Api\ProfileController::class, 'show']);
    Route::put('/user', [App\Http\Controllers\Api\ProfileController::class, 'update']);
    Route::put('/user/password', [App\Http\Controllers\Api\ProfileController::class, 'updatePassword']);

    // Tiket
    Route::post('tiket', [App\Http\Controllers\Api\TiketController::class, 'store']);
    Route::get('my-tickets', [App\Http\Controllers\Api\TiketController::class, 'myTickets']);

    // Verifikasi tiket (petugas)
    Route::post('tiket/{kode_tiket}/verify', [App\Http\Controllers\Api\TiketController::class, 'verify']);
    Route::post('tiket/{kode_tiket}/checkin', [App\Http\Controllers\Api\TiketController::class, 'checkin']);

    // Pembayaran
    Route::post('pembayaran', [App\Http\Controllers\Api\PembayaranController::class, 'store']);
    Route::get('pembayaran', [App\Http\Controllers\Api\PembayaranController::class, 'index']);
    Route::get('pembayaran/{id}', [App\Http\Controllers\Api\PembayaranController::class, 'show']);
    Route::get('pembayaran/{id}/check-status', [App\Http\Controllers\Api\PembayaranController::class, 'checkStatus']);

    // Upload
    Route::post('upload/profile', [App\Http\Controllers\Api\UploadController::class, 'uploadProfile']);
    Route::post('upload/bukti-pembayaran', [App\Http\Controllers\Api\UploadController::class, 'uploadBuktiPembayaran']);
});

// =================== ADMIN/AGENT ENDPOINTS ===================
Route::middleware(['auth:sanctum', 'role:owner|agent'])->group(function () {
    // Bus Management
    Route::post('bus', [App\Http\Controllers\Api\BusController::class, 'store']);
    Route::put('bus/{id}', [App\Http\Controllers\Api\BusController::class, 'update']);
    Route::delete('bus/{id}', [App\Http\Controllers\Api\BusController::class, 'destroy']);

    // Fasilitas Management
    Route::post('fasilitas', [App\Http\Controllers\Api\FasilitasController::class, 'store']);
    Route::put('fasilitas/{id}', [App\Http\Controllers\Api\FasilitasController::class, 'update']);
    Route::delete('fasilitas/{id}', [App\Http\Controllers\Api\FasilitasController::class, 'destroy']);

    // Terminal Management
    Route::post('terminal', [App\Http\Controllers\Api\TerminalController::class, 'store']);
    Route::put('terminal/{id}', [App\Http\Controllers\Api\TerminalController::class, 'update']);
    Route::delete('terminal/{id}', [App\Http\Controllers\Api\TerminalController::class, 'destroy']);

    // Rute Management
    Route::post('rute', [App\Http\Controllers\Api\RuteController::class, 'store']);
    Route::put('rute/{id}', [App\Http\Controllers\Api\RuteController::class, 'update']);
    Route::delete('rute/{id}', [App\Http\Controllers\Api\RuteController::class, 'destroy']);

    // Sopir Management
    Route::apiResource('sopir', App\Http\Controllers\Api\SopirController::class);

    // Kelas Bus Management
    Route::apiResource('kelas-bus', App\Http\Controllers\Api\KelasBusController::class);

    // Jadwal Management
    Route::post('jadwal', [App\Http\Controllers\Api\JadwalController::class, 'store']);
    Route::put('jadwal/{id}', [App\Http\Controllers\Api\JadwalController::class, 'update']);
    Route::delete('jadwal/{id}', [App\Http\Controllers\Api\JadwalController::class, 'destroy']);

    // Jadwal Kelas Bus Management
    Route::apiResource('jadwal-kelas-bus', App\Http\Controllers\Api\JadwalKelasBusController::class);

    // Laporan & Analytics
    Route::get('laporan/tiket', [App\Http\Controllers\Api\LaporanController::class, 'tiket']);
    Route::get('laporan/pendapatan', [App\Http\Controllers\Api\LaporanController::class, 'pendapatan']);
    Route::get('laporan/penumpang', [App\Http\Controllers\Api\LaporanController::class, 'penumpang']);

    // Upload (Admin/Agent)
    Route::post('upload/bus-photo', [App\Http\Controllers\Api\UploadController::class, 'uploadBusPhoto']);
    Route::delete('upload/bus-photo/{id}', [App\Http\Controllers\Api\UploadController::class, 'deleteBusPhoto']);
    Route::post('upload/terminal-photo', [App\Http\Controllers\Api\UploadController::class, 'uploadTerminalPhoto']);
    Route::delete('upload/terminal-photo/{id}', [App\Http\Controllers\Api\UploadController::class, 'deleteTerminalPhoto']);

    // Pembayaran manual approval
    Route::post('pembayaran/{id}/approve', [App\Http\Controllers\Api\PembayaranController::class, 'approveManual']);
    Route::post('pembayaran/{id}/reject', [App\Http\Controllers\Api\PembayaranController::class, 'rejectManual']);
});
