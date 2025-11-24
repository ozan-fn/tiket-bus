<?php

use App\Http\Controllers\BusController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SopirController;
use App\Http\Controllers\TerminalController;
use App\Http\Controllers\RuteController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\PemesananController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Pemesanan tiket for authenticated users
    Route::get('pemesanan', [PemesananController::class, 'index'])->name('pemesanan.index');
    Route::get('pemesanan/{jadwal}', [PemesananController::class, 'create'])->name('pemesanan.create');
    Route::post('pemesanan/{jadwal}', [PemesananController::class, 'store'])->name('pemesanan.store');
    Route::get('tiket/{tiket}', [PemesananController::class, 'show'])->name('pemesanan.show');

    // Fasilitas CRUD for super admin
    Route::resource('admin/fasilitas', FasilitasController::class)->parameters(['fasilitas' => 'fasilitas'])->names('admin/fasilitas')->middleware('role:super_admin');

    // Bus CRUD for super admin
    Route::resource('admin/bus', BusController::class)->parameters(['bus' => 'bus'])->names('admin/bus')->middleware('role:super_admin');

    // Sopir CRUD for super admin
    Route::get('admin/sopir/search-users', [SopirController::class, 'searchUsers'])->name('admin.sopir.search-users')->middleware('role:super_admin');
    Route::resource('admin/sopir', SopirController::class)->parameters(['sopir' => 'sopir'])->names('admin/sopir')->middleware('role:super_admin');

    // Terminal CRUD for super admin
    Route::resource('admin/terminal', TerminalController::class)->parameters(['terminal' => 'terminal'])->names('admin/terminal')->middleware('role:super_admin');

    // Rute CRUD for super admin
    Route::resource('admin/rute', RuteController::class)->parameters(['rute' => 'rute'])->names('admin/rute')->middleware('role:super_admin');

    // Jadwal CRUD for super admin
    Route::resource('admin/jadwal', JadwalController::class)->parameters(['jadwal' => 'jadwal'])->names('admin/jadwal')->middleware('role:super_admin');

    // History Pemesanan for admin
    Route::get('admin/history-pemesanan', [PemesananController::class, 'history'])->name('admin.history-pemesanan')->middleware('role:admin|super_admin');
});

require __DIR__ . '/auth.php';
