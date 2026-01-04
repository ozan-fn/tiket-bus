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
     * Display the dashboard for owner/agent/driver/user.
     *
     * Prepares summary metrics used by the dashboard view and its role-specific
     * partials (e.g. dashboard.owner, dashboard.driver).
     */
    public function index(Request $request)
    {
        // Determine role
        $userRole = auth()->user()?->roles->first()?->name ?? "user";

        // Auto-assign 'user' role jika user belum punya role
        $authUser = auth()->user();
        if ($authUser && !$authUser->roles()->exists()) {
            $userRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
            $authUser->assignRole($userRole);
            $userRole = 'user';
        }

        // Route to appropriate dashboard based on role
        if ($userRole === "owner") {
            return $this->ownerDashboard();
        } elseif ($userRole === "agent") {
            return $this->agentDashboard();
        } elseif ($userRole === "conductor") {
            return $this->conductorDashboard();
        } elseif ($userRole === "driver") {
            return $this->driverDashboard();
        } else {
            // Default to user dashboard
            return $this->userDashboard();
        }
    }

    private function ownerDashboard()
    {
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

        $userRole = "owner";

        return view("dashboard.owner", compact(
            "totalBus",
            "totalTerminal",
            "totalRute",
            "totalJadwal",
            "totalTiket",
            "totalPendapatan",
            "totalPenumpang",
            "totalSopir",
            "recentTikets",
            "userRole"
        ));
    }

    private function agentDashboard()
    {
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

        // Recent tickets for quick activity (latest 10)
        $recentTikets = Tiket::with(["jadwalKelasBus.kelasBus", "jadwalKelasBus.jadwal.rute.asalTerminal", "jadwalKelasBus.jadwal.rute.tujuanTerminal", "user", "pembayaran"])
            ->orderByDesc("waktu_pesan")
            ->limit(10)
            ->get();

        $userRole = "agent";

        return view("dashboard.agent", compact(
            "totalBus",
            "totalTerminal",
            "totalRute",
            "totalJadwal",
            "totalTiket",
            "totalPendapatan",
            "totalPenumpang",
            "recentTikets",
            "userRole"
        ));
    }

    private function conductorDashboard()
    {
        $userRole = "conductor";

        return view("dashboard.conductor", compact("userRole"));
    }

    private function userDashboard()
    {
        $currentUser = auth()->user();

        // Active tickets (dibayar atau digunakan)
        $activeTickets = Tiket::where("user_id", $currentUser->id)
            ->whereIn("status", ["dibayar", "digunakan"])
            ->count();

        // Completed trips (selesai)
        $completedTrips = Tiket::where("user_id", $currentUser->id)
            ->where("status", "selesai")
            ->count();

        // Total spent
        $totalSpent = Tiket::where("user_id", $currentUser->id)
            ->whereIn("status", ["dibayar", "digunakan", "selesai"])
            ->sum("harga");

        // Upcoming tickets (dibayar/digunakan dengan jadwal masih akan datang)
        $upcomingTickets = Tiket::with([
            "jadwalKelasBus.kelasBus",
            "jadwalKelasBus.jadwal.rute.asalTerminal",
            "jadwalKelasBus.jadwal.rute.tujuanTerminal",
            "jadwalKelasBus.busKelasBus.bus",
            "kursi",
            "pembayaran"
        ])
            ->where("user_id", $currentUser->id)
            ->whereIn("status", ["dibayar", "digunakan"])
            ->whereHas("jadwalKelasBus.jadwal", function ($q) {
                $q->where("tanggal_berangkat", ">=", now()->toDateString());
            })
            ->orderBy("waktu_pesan", "desc")
            ->get();

        // Completed tickets (selesai)
        $completedTickets = Tiket::with([
            "jadwalKelasBus.kelasBus",
            "jadwalKelasBus.jadwal.rute.asalTerminal",
            "jadwalKelasBus.jadwal.rute.tujuanTerminal",
            "jadwalKelasBus.busKelasBus.bus",
            "kursi"
        ])
            ->where("user_id", $currentUser->id)
            ->where("status", "selesai")
            ->orderBy("waktu_pesan", "desc")
            ->limit(10)
            ->get();

        $userRole = "user";

        return view("dashboard.user", compact(
            "activeTickets",
            "completedTrips",
            "totalSpent",
            "upcomingTickets",
            "completedTickets",
            "userRole"
        ));
    }

    private function driverDashboard()
    {
        $user = auth()->user();
        $sopir = $user->sopir;

        // Ambil jadwal terbaru sopir (aktif atau yang akan datang)
        $jadwalAktif = Jadwal::where("sopir_id", $sopir->id)
            ->where("status", "aktif")
            ->orderBy("tanggal_berangkat", "desc")
            ->orderBy("jam_berangkat", "desc")
            ->with([
                "bus",
                "rute.asalTerminal",
                "rute.tujuanTerminal",
                "jadwalKelasBus" => function ($query) {
                    $query->with([
                        "kelasBus",
                        "tikets" => function ($t) {
                            $t->where("status", "dibayar");
                        }
                    ]);
                }
            ])
            ->first();

        // Ambil jadwal-jadwal mendatang
        $jadwalMendatang = Jadwal::where("sopir_id", $sopir->id)
            ->where("status", "aktif")
            ->whereRaw("CONCAT(DATE(tanggal_berangkat), ' ', TIME(jam_berangkat)) > NOW()")
            ->orderBy("tanggal_berangkat", "asc")
            ->orderBy("jam_berangkat", "asc")
            ->with(["bus", "rute.asalTerminal", "rute.tujuanTerminal"])
            ->limit(5)
            ->get();

        // Hitung statistik
        $totalJadwal = Jadwal::where("sopir_id", $sopir->id)->count();
        $jadwalSelesai = Jadwal::where("sopir_id", $sopir->id)->where("status", "selesai")->count();

        $userRole = "driver";

        return view("dashboard", compact(
            "jadwalAktif",
            "jadwalMendatang",
            "totalJadwal",
            "jadwalSelesai",
            "sopir",
            "userRole",
            "user"
        ));
    }
}
