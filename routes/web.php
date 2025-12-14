<?php

use App\Http\Controllers\BusController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SopirController;
use App\Http\Controllers\TerminalController;
use App\Http\Controllers\RuteController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\KelasBusController;
use App\Http\Controllers\JadwalKelasBusController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\CekKursiController;
use App\Http\Controllers\PembayaranManualController;
use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    return view("welcome");
});

Route::get("/dashboard", function () {
    return view("dashboard");
})
    ->middleware(["auth", "verified", "role:owner|agent"])
    ->name("dashboard");

Route::middleware("auth")->group(function () {
    // Profile Routes
    Route::get("/profile", [ProfileController::class, "edit"])->name("profile.edit");
    Route::patch("/profile", [ProfileController::class, "update"])->name("profile.update");
    Route::delete("/profile", [ProfileController::class, "destroy"])->name("profile.destroy");

    // Pemesanan tiket for authenticated users
    Route::get("pemesanan", [PemesananController::class, "index"])->name("pemesanan.index");
    Route::get("pemesanan/{jadwal}", [PemesananController::class, "create"])->name("pemesanan.create");
    Route::post("pemesanan/{jadwal}", [PemesananController::class, "store"])->name("pemesanan.store");
    Route::get("tiket/{tiket}", [PemesananController::class, "show"])->name("pemesanan.show");
});

// =================== ADMIN/OWNER/AGENT ROUTES ===================
Route::bind("terminalPhoto", function ($value) {
    return \App\Models\TerminalPhoto::findOrFail($value);
});

Route::bind("busPhoto", function ($value) {
    return \App\Models\BusPhoto::findOrFail($value);
});

Route::middleware(["auth", "verified", "role:owner|agent"])
    ->prefix("admin")
    ->name("admin/")
    ->group(function () {
        // Bus Management
        Route::resource("bus", BusController::class)->parameters(["bus" => "bus"]);
        Route::delete("bus-photo/{busPhoto}", [BusController::class, "destroyPhoto"])->name("bus-photo.destroy");

        // Fasilitas Management
        Route::resource("fasilitas", FasilitasController::class)->parameters(["fasilitas" => "fasilitas"]);

        // Sopir Management
        Route::get("sopir/search-users", [SopirController::class, "searchUsers"])->name("sopir.search-users");
        Route::resource("sopir", SopirController::class)->parameters(["sopir" => "sopir"]);

        // Terminal Management
        Route::resource("terminal", TerminalController::class)->parameters(["terminal" => "terminal"]);
        Route::delete("terminal-photo/{terminalPhoto}", [TerminalController::class, "destroyPhoto"])->name("terminal-photo.destroy");

        // Rute Management
        Route::resource("rute", RuteController::class)->parameters(["rute" => "rute"]);

        // Jadwal Management
        Route::resource("jadwal", JadwalController::class)->parameters(["jadwal" => "jadwal"]);

        // Pemesanan Tiket (Agent Booking)
        Route::get("pemesanan", [PemesananController::class, "adminIndex"])->name("pemesanan.index");
        Route::get("pemesanan/create/{jadwal}", [PemesananController::class, "adminCreate"])->name("pemesanan.create");
        Route::post("pemesanan/store/{jadwal}", [PemesananController::class, "adminStore"])->name("pemesanan.store");

        // Pembayaran Manual
        Route::get("pembayaran-manual", [PembayaranManualController::class, "index"])->name("pembayaran-manual");

        // History Pemesanan
        Route::get("history-pemesanan", [PemesananController::class, "history"])->name("history-pemesanan");

        // Scan Tiket
        Route::get("scan", [ScanController::class, "index"])->name("scan.index");
        Route::post("scan/verify", [ScanController::class, "verifyTicket"])->name("scan.verify");

        // Cek Ketersediaan Kursi
        Route::get("cek-kursi", [CekKursiController::class, "index"])->name("cek-kursi.index");
        Route::get("cek-kursi/get-kursi", [CekKursiController::class, "getKursi"])->name("cek-kursi.get-kursi");

        // Kelas Bus Management
        Route::resource("kelas-bus", KelasBusController::class)->parameters(["kelas-bus" => "kelasBus"]);

        // Jadwal Kelas Bus Management
        Route::get("jadwal-kelas-bus/kelas-by-jadwal/{jadwal_id}", [JadwalKelasBusController::class, "getKelasByJadwal"])->name("jadwal-kelas-bus.kelas-by-jadwal");
        Route::resource("jadwal-kelas-bus", JadwalKelasBusController::class)->parameters(["jadwal-kelas-bus" => "jadwalKelasBu"]);

        // Laporan & Analytics
        Route::get("laporan", [LaporanController::class, "index"])->name("laporan.index");
        Route::get("laporan/tiket", [LaporanController::class, "tiket"])->name("laporan.tiket");
        Route::get("laporan/pendapatan", [LaporanController::class, "pendapatan"])->name("laporan.pendapatan");
        Route::get("laporan/penumpang", [LaporanController::class, "penumpang"])->name("laporan.penumpang");
    });

require __DIR__ . "/auth.php";
