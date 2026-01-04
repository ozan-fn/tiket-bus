<?php

use App\Http\Controllers\UserController;

use App\Http\Controllers\BannerController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\DashboardController;
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
use App\Http\Controllers\TiketController;
use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    return view("home.index");
});

Route::get("/dashboard", [DashboardController::class, "index"])
    ->middleware(["auth", "verified"])
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

    // Pembayaran tiket
    Route::get("pemesanan/{tiket}/pembayaran", [PemesananController::class, "pembayaran"])->name("pemesanan.pembayaran");
    Route::post("pemesanan/{tiket}/pembayaran", [PemesananController::class, "pembayaranStore"])->name("pemesanan.pembayaran.store");

    // Pembayaran instruksi dan tunai
    Route::get("pembayaran/{pembayaran}/instruksi", [PemesananController::class, "pembayaranInstruksi"])->name("pembayaran.instruksi");
    Route::get("pembayaran/{pembayaran}/tunai", [PemesananController::class, "pembayaranTunai"])->name("pembayaran.tunai");

    // Tiket list dan detail
    Route::get("tiket", [TiketController::class, "index"])->name("tiket.index");
    Route::get("tiket/{tiket}", [TiketController::class, "show"])->name("tiket.show");

    // Old route for backward compatibility
    Route::get("tiket-detail/{tiket}", [PemesananController::class, "show"])->name("pemesanan.show");
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
        // User Management (Owner Only)
        Route::middleware("role:owner")->group(function () {
            Route::resource("user", UserController::class)->names([
                "index" => "user.index",
                "create" => "user.create",
                "store" => "user.store",
                "edit" => "user.edit",
                "update" => "user.update",
                "destroy" => "user.destroy",
            ]);
        });

        // Bus Management
        Route::resource("bus", BusController::class)->parameters(["bus" => "bus"]);
        Route::delete("bus-photo/{busPhoto}", [BusController::class, "destroyPhoto"])->name("bus-photo.destroy");

        // Fasilitas Management
        Route::resource("fasilitas", FasilitasController::class)->parameters(["fasilitas" => "fasilitas"]);

        // Sopir Management
        Route::get("sopir/search-users", [SopirController::class, "searchUsers"])->name("sopir.search-users");
        Route::resource("sopir", SopirController::class)->parameters(["sopir" => "sopir"]);

        // Banner Management
        Route::resource("banner", BannerController::class)->parameters(["banner" => "banner"]);
        Route::post("banner/{banner}/order/{direction}", [BannerController::class, "order"])->name("banner.order");

        // Terminal Management
        Route::resource("terminal", TerminalController::class)->parameters(["terminal" => "terminal"]);
        Route::delete("terminal-photo/{terminalPhoto}", [TerminalController::class, "destroyPhoto"])->name("terminal-photo.destroy");

        // Rute Management
        Route::resource("rute", RuteController::class)->parameters(["rute" => "rute"]);

        // Jadwal Management
        Route::resource("jadwal", JadwalController::class)->parameters(["jadwal" => "jadwal"]);
        Route::get("jadwal/get-kelas-by-bus/{bus}", [JadwalController::class, "getKelasByBus"])->name("jadwal.get-kelas-by-bus");

        // Pemesanan Tiket (Agent Booking)
        Route::get("pemesanan", [PemesananController::class, "adminIndex"])->name("pemesanan.index");
        Route::get("pemesanan/create/{jadwal}", [PemesananController::class, "adminCreate"])->name("pemesanan.create");
        Route::post("pemesanan/store/{jadwal}", [PemesananController::class, "adminStore"])->name("pemesanan.store");
        Route::get("pemesanan/{tiket}", [PemesananController::class, "adminShow"])->name("pemesanan.show");

        // History Pemesanan
        Route::get("history-pemesanan", [PemesananController::class, "history"])->name("history-pemesanan");

        // Pembayaran Manual
        Route::get("pembayaran-manual", [PembayaranManualController::class, "index"])->name("pembayaran-manual.index");
        Route::get("pembayaran-manual/{pembayaran}", [PembayaranManualController::class, "show"])->name("pembayaran-manual.show");
        Route::get("pembayaran-manual/{pembayaran}/edit", [PembayaranManualController::class, "edit"])->name("pembayaran-manual.edit");
        Route::put("pembayaran-manual/{pembayaran}", [PembayaranManualController::class, "update"])->name("pembayaran-manual.update");
        Route::post("pembayaran-manual/{pembayaran}/confirm", [PembayaranManualController::class, "confirm"])->name("pembayaran-manual.confirm");
        Route::delete("pembayaran-manual/{pembayaran}", [PembayaranManualController::class, "destroy"])->name("pembayaran-manual.destroy");

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

        // Laporan & Analytics (Owner Only)
        Route::middleware("role:owner")->group(function () {
            Route::get("laporan", [LaporanController::class, "index"])->name("laporan.index");
            Route::get("laporan/tiket", [LaporanController::class, "tiket"])->name("laporan.tiket");
            Route::get("laporan/pendapatan", [LaporanController::class, "pendapatan"])->name("laporan.pendapatan");
            Route::get("laporan/penumpang", [LaporanController::class, "penumpang"])->name("laporan.penumpang");
        });
    });

require __DIR__ . "/auth.php";
