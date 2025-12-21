<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// =================== AUTH & USER ===================
Route::post("/register", [App\Http\Controllers\Api\AuthController::class, "register"]);
Route::post("/login", [App\Http\Controllers\Api\AuthController::class, "login"]);
Route::get("/auth/google", [App\Http\Controllers\Api\GoogleAuthController::class, "redirect"]);
Route::get("/auth/google/callback", [App\Http\Controllers\Api\GoogleAuthController::class, "callback"]);

// =================== PUBLIC ENDPOINTS ===================
// Bus & Fasilitas (Public Read)
Route::get("bus", [App\Http\Controllers\Api\BusController::class, "index"]);
Route::get("bus/{id}", [App\Http\Controllers\Api\BusController::class, "show"]);
Route::get("fasilitas", [App\Http\Controllers\Api\FasilitasController::class, "index"]);

// Terminal & Rute
Route::get("terminal", [App\Http\Controllers\Api\TerminalController::class, "index"]);
Route::get("terminal/{id}", [App\Http\Controllers\Api\TerminalController::class, "show"]);
Route::get("rute", [App\Http\Controllers\Api\RuteController::class, "index"]);
Route::get("rute/{id}", [App\Http\Controllers\Api\RuteController::class, "show"]);

// Banner - public endpoints (singular & plural)
// Public list (singular path)
Route::get("banner", [App\Http\Controllers\BannerController::class, "publicIndex"]);
// Public show (singular path) - returns a single banner by id (implement `publicShow` in controller)
Route::get("banner/{id}", [App\Http\Controllers\BannerController::class, "publicShow"]);

// Jadwal
Route::get("jadwal", [App\Http\Controllers\Api\JadwalController::class, "index"]);
Route::get("jadwal/{id}", [App\Http\Controllers\Api\JadwalController::class, "show"]);

// Kursi & Layout
Route::get("jadwal/{jadwal_id}/kursi", [App\Http\Controllers\Api\KursiController::class, "getKursiByJadwal"]);
Route::get("kursi/{kursi_id}/check", [App\Http\Controllers\Api\KursiController::class, "checkKetersediaan"]);

// Tiket Public (cek by kode)
Route::get("tiket/{kode_tiket}", [App\Http\Controllers\Api\TiketController::class, "show"]);
Route::get("tiket/{kode_tiket}/verify", [App\Http\Controllers\Api\TiketController::class, "verify"]);

// Pembayaran Callback (dari payment gateway)
Route::post("pembayaran/callback", [App\Http\Controllers\Api\PembayaranController::class, "callback"]);

// =================== USER ENDPOINTS ===================
Route::middleware(["auth:sanctum"])->group(function () {
    // Auth
    Route::post("/logout", [App\Http\Controllers\Api\AuthController::class, "logout"]);

    // Profile
    Route::get("/user", [App\Http\Controllers\Api\ProfileController::class, "show"]);
    Route::put("/user", [App\Http\Controllers\Api\ProfileController::class, "update"]);
    Route::put("/user/password", [App\Http\Controllers\Api\ProfileController::class, "updatePassword"]);

    // Profile alias (untuk backward compatibility)
    Route::get("/profile", [App\Http\Controllers\Api\ProfileController::class, "show"]);
    Route::put("/profile", [App\Http\Controllers\Api\ProfileController::class, "update"]);
    Route::put("/profile/password", [App\Http\Controllers\Api\ProfileController::class, "updatePassword"]);

    // Tiket
    Route::post("tiket", [App\Http\Controllers\Api\TiketController::class, "store"]);
    Route::get("my-tickets", [App\Http\Controllers\Api\TiketController::class, "myTickets"]);
    Route::get("tiket/detail/{id}", [App\Http\Controllers\Api\TiketController::class, "showById"]);

    // Verifikasi tiket (petugas)
    Route::post("tiket/{kode_tiket}/verify", [App\Http\Controllers\Api\TiketController::class, "verify"]);
    Route::post("tiket/{kode_tiket}/checkin", [App\Http\Controllers\Api\TiketController::class, "checkin"]);

    // Pembayaran
    Route::post("pembayaran", [App\Http\Controllers\Api\PembayaranController::class, "store"]);
    Route::get("pembayaran", [App\Http\Controllers\Api\PembayaranController::class, "index"]);
    Route::get("pembayaran/{id}", [App\Http\Controllers\Api\PembayaranController::class, "show"]);
    Route::get("pembayaran/{id}/check-status", [App\Http\Controllers\Api\PembayaranController::class, "checkStatus"]);

    // Upload
    Route::post("upload/profile", [App\Http\Controllers\Api\UploadController::class, "uploadProfile"]);
    Route::post("upload/bukti-pembayaran", [App\Http\Controllers\Api\UploadController::class, "uploadBuktiPembayaran"]);
});

// =================== DRIVER ENDPOINTS ===================
Route::middleware(["auth:sanctum", "role:driver"])->group(function () {
    Route::get("driver", [App\Http\Controllers\Api\DriverController::class, "index"]);
    Route::get("driver/kursi/{jadwal}", [App\Http\Controllers\Api\DriverController::class, "kursi"]);
    Route::post("driver/verify/{kode}", [App\Http\Controllers\Api\DriverController::class, "verify"]);
});

// =================== OWNER ENDPOINTS ===================
Route::middleware(["auth:sanctum", "role:owner"])->group(function () {
    Route::apiResource("banners", App\Http\Controllers\BannerController::class);
});
