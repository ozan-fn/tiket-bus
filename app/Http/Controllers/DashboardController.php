<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tiket;
use App\Models\Bus;
use App\Models\Terminal;
use App\Models\Rute;
use App\Models\Jadwal;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Display the dashboard for owner/agent.
     *
     * Prepares summary metrics used by the dashboard view and its role-specific
     * partials (e.g. dashboard.owner).
     */
    public function index(Request $request)
    {
        // Determine role (kept for compatibility with views that may expect it)
        $userRole = auth()->user()?->roles->first()?->name ?? "agent";

        // Basic counts
        $totalBus = Bus::count();
        $totalTerminal = Terminal::count();
        $totalRute = Rute::count();
        $totalJadwal = Jadwal::count();

        // Ticket and revenue related stats
        $paidStatuses = ["dibayar", "digunakan", "selesai"];

        $totalTiket = Tiket::count();
        $totalPendapatan = Tiket::whereIn("status", $paidStatuses)->sum("harga");

        // Distinct passengers who have paid/used/completed tickets
        $totalPenumpang = Tiket::whereIn("status", $paidStatuses)->distinct("user_id")->count("user_id");

        // Count users with role 'sopir' (driver)
        $totalSopir = User::whereHas("roles", function ($q) {
            $q->where("name", "sopir");
        })->count();

        // Recent tickets for quick activity (latest 10)
        $recentTikets = Tiket::with(["jadwalKelasBus.kelasBus", "jadwalKelasBus.jadwal.rute.asalTerminal", "jadwalKelasBus.jadwal.rute.tujuanTerminal", "user", "pembayaran"])
            ->orderByDesc("waktu_pesan")
            ->limit(10)
            ->get();

        return view("dashboard", compact("totalBus", "totalTerminal", "totalRute", "totalJadwal", "totalPendapatan", "totalTiket", "totalPenumpang", "totalSopir", "recentTikets", "userRole"));
    }
}
