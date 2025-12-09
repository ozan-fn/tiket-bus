<?php

namespace App\Http\Controllers;

use App\Models\Tiket;
use App\Models\Jadwal;
use App\Models\Bus;
use App\Models\Rute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Display analytics dashboard
     */
    public function index(Request $request)
    {
        $periode = $request->input("periode", "30"); // default 30 hari

        // Statistik Umum
        $totalTiket = Tiket::count();
        $totalPendapatan = Tiket::whereIn("status", ["dibayar", "digunakan"])->sum("harga");
        $totalPenumpang = Tiket::whereIn("status", ["dibayar", "digunakan"])
            ->distinct("user_id")
            ->count("user_id");
        $totalBus = Bus::count();

        // Tiket per Status (Pie Chart)
        $tiketPerStatus = Tiket::select("status", DB::raw("count(*) as total"))
            ->groupBy("status")
            ->get()
            ->mapWithKeys(function ($item) {
                return [ucfirst($item->status) => $item->total];
            });

        // Pendapatan per Bulan (Line Chart) - 6 bulan terakhir
        $pendapatanPerBulan = Tiket::select(DB::raw('DATE_FORMAT(waktu_pesan, "%Y-%m") as bulan'), DB::raw("SUM(harga) as total"))
            ->whereIn("status", ["dibayar", "digunakan"])
            ->where("waktu_pesan", ">=", now()->subMonths(6))
            ->groupBy("bulan")
            ->orderBy("bulan")
            ->get()
            ->mapWithKeys(function ($item) {
                return [Carbon::parse($item->bulan)->format("M Y") => $item->total];
            });

        // Tiket Terjual per Hari (Bar Chart) - periode dipilih
        $tiketPerHari = Tiket::select(DB::raw("DATE(waktu_pesan) as tanggal"), DB::raw("COUNT(*) as total"))
            ->where("waktu_pesan", ">=", now()->subDays($periode))
            ->whereIn("status", ["dibayar", "digunakan"])
            ->groupBy("tanggal")
            ->orderBy("tanggal")
            ->get()
            ->mapWithKeys(function ($item) {
                return [Carbon::parse($item->tanggal)->format("d M") => $item->total];
            });

        // Rute Terpopuler (Top 5)
        $ruteTerpopuler = Tiket::select("jadwal_kelas_bus.jadwal_id", DB::raw("COUNT(tiket.id) as total_tiket"), DB::raw("SUM(tiket.harga) as total_pendapatan"))
            ->join("jadwal_kelas_bus", "tiket.jadwal_kelas_bus_id", "=", "jadwal_kelas_bus.id")
            ->join("jadwal", "jadwal_kelas_bus.jadwal_id", "=", "jadwal.id")
            ->join("rute", "jadwal.rute_id", "=", "rute.id")
            ->join("terminal as asal", "rute.asal_terminal_id", "=", "asal.id")
            ->join("terminal as tujuan", "rute.tujuan_terminal_id", "=", "tujuan.id")
            ->whereIn("tiket.status", ["dibayar", "digunakan"])
            ->where("tiket.waktu_pesan", ">=", now()->subDays($periode))
            ->groupBy("jadwal_kelas_bus.jadwal_id", "asal.nama_terminal", "tujuan.nama_terminal")
            ->orderByDesc("total_tiket")
            ->limit(5)
            ->get(["asal.nama_terminal as asal", "tujuan.nama_terminal as tujuan", "total_tiket", "total_pendapatan"]);

        // Bus dengan Occupancy Tertinggi
        $busOccupancy = Tiket::select("bus.nama as nama_bus", DB::raw("COUNT(tiket.id) as total_tiket"), DB::raw("SUM(tiket.harga) as total_pendapatan"))
            ->join("jadwal_kelas_bus", "tiket.jadwal_kelas_bus_id", "=", "jadwal_kelas_bus.id")
            ->join("jadwal", "jadwal_kelas_bus.jadwal_id", "=", "jadwal.id")
            ->join("bus", "jadwal.bus_id", "=", "bus.id")
            ->whereIn("tiket.status", ["dibayar", "digunakan"])
            ->where("tiket.waktu_pesan", ">=", now()->subDays($periode))
            ->groupBy("bus.id", "bus.nama")
            ->orderByDesc("total_tiket")
            ->limit(5)
            ->get();

        return view("laporan.index", compact("totalTiket", "totalPendapatan", "totalPenumpang", "totalBus", "tiketPerStatus", "pendapatanPerBulan", "tiketPerHari", "ruteTerpopuler", "busOccupancy", "periode"));
    }

    /**
     * Laporan Tiket
     */
    public function tiket(Request $request)
    {
        $startDate = $request->input("start_date", now()->subMonth()->format("Y-m-d"));
        $endDate = $request->input("end_date", now()->format("Y-m-d"));
        $status = $request->input("status");

        $query = Tiket::with(["jadwalKelasBus.jadwal.rute.asalTerminal", "jadwalKelasBus.jadwal.rute.tujuanTerminal", "jadwalKelasBus.kelasBus", "user"])->whereBetween("waktu_pesan", [$startDate, $endDate]);

        if ($status) {
            $query->where("status", $status);
        }

        $tikets = $query->latest("waktu_pesan")->paginate(20);

        // Summary
        $totalTiket = $query->count();
        $totalPendapatan = Tiket::whereBetween("waktu_pesan", [$startDate, $endDate])
            ->whereIn("status", ["dibayar", "digunakan"])
            ->sum("harga");

        return view("laporan.tiket", compact("tikets", "totalTiket", "totalPendapatan", "startDate", "endDate", "status"));
    }

    /**
     * Laporan Pendapatan
     */
    public function pendapatan(Request $request)
    {
        $startDate = $request->input("start_date", now()->subMonth()->format("Y-m-d"));
        $endDate = $request->input("end_date", now()->format("Y-m-d"));
        $groupBy = $request->input("group_by", "day"); // day, month, year

        // Pendapatan per periode
        $format = match ($groupBy) {
            "month" => "%Y-%m",
            "year" => "%Y",
            default => "%Y-%m-%d",
        };

        $pendapatan = Tiket::select(DB::raw("DATE_FORMAT(waktu_pesan, '{$format}') as periode"), DB::raw("COUNT(*) as jumlah_tiket"), DB::raw("SUM(harga) as total_pendapatan"))
            ->whereIn("status", ["dibayar", "digunakan"])
            ->whereBetween("waktu_pesan", [$startDate, $endDate])
            ->groupBy("periode")
            ->orderBy("periode")
            ->get();

        // Pendapatan per Rute
        $pendapatanPerRute = Tiket::select("asal.nama_terminal as asal", "tujuan.nama_terminal as tujuan", DB::raw("COUNT(tiket.id) as jumlah_tiket"), DB::raw("SUM(tiket.harga) as total_pendapatan"))
            ->join("jadwal_kelas_bus", "tiket.jadwal_kelas_bus_id", "=", "jadwal_kelas_bus.id")
            ->join("jadwal", "jadwal_kelas_bus.jadwal_id", "=", "jadwal.id")
            ->join("rute", "jadwal.rute_id", "=", "rute.id")
            ->join("terminal as asal", "rute.asal_terminal_id", "=", "asal.id")
            ->join("terminal as tujuan", "rute.tujuan_terminal_id", "=", "tujuan.id")
            ->whereIn("tiket.status", ["dibayar", "digunakan"])
            ->whereBetween("tiket.waktu_pesan", [$startDate, $endDate])
            ->groupBy("asal.nama_terminal", "tujuan.nama_terminal")
            ->orderByDesc("total_pendapatan")
            ->get();

        $totalPendapatan = $pendapatan->sum("total_pendapatan");
        $totalTiket = $pendapatan->sum("jumlah_tiket");

        return view("laporan.pendapatan", compact("pendapatan", "pendapatanPerRute", "totalPendapatan", "totalTiket", "startDate", "endDate", "groupBy"));
    }

    /**
     * Laporan Penumpang
     */
    public function penumpang(Request $request)
    {
        $startDate = $request->input("start_date", now()->subMonth()->format("Y-m-d"));
        $endDate = $request->input("end_date", now()->format("Y-m-d"));

        // Total Penumpang
        $totalPenumpang = Tiket::whereBetween("waktu_pesan", [$startDate, $endDate])
            ->whereIn("status", ["dibayar", "digunakan"])
            ->count();

        // Penumpang per Hari
        $penumpangPerHari = Tiket::select(DB::raw("DATE(waktu_pesan) as tanggal"), DB::raw("COUNT(*) as jumlah"))
            ->whereIn("status", ["dibayar", "digunakan"])
            ->whereBetween("waktu_pesan", [$startDate, $endDate])
            ->groupBy("tanggal")
            ->orderBy("tanggal")
            ->get();

        // Penumpang per Rute
        $penumpangPerRute = Tiket::select("asal.nama_terminal as asal", "tujuan.nama_terminal as tujuan", DB::raw("COUNT(tiket.id) as jumlah_penumpang"))
            ->join("jadwal_kelas_bus", "tiket.jadwal_kelas_bus_id", "=", "jadwal_kelas_bus.id")
            ->join("jadwal", "jadwal_kelas_bus.jadwal_id", "=", "jadwal.id")
            ->join("rute", "jadwal.rute_id", "=", "rute.id")
            ->join("terminal as asal", "rute.asal_terminal_id", "=", "asal.id")
            ->join("terminal as tujuan", "rute.tujuan_terminal_id", "=", "tujuan.id")
            ->whereIn("tiket.status", ["dibayar", "digunakan"])
            ->whereBetween("tiket.waktu_pesan", [$startDate, $endDate])
            ->groupBy("asal.nama_terminal", "tujuan.nama_terminal")
            ->orderByDesc("jumlah_penumpang")
            ->get();

        // Top Penumpang (User yang paling sering pesan)
        $topPenumpang = Tiket::select("users.name", "users.email", DB::raw("COUNT(tiket.id) as total_tiket"), DB::raw("SUM(tiket.harga) as total_pengeluaran"))
            ->join("users", "tiket.user_id", "=", "users.id")
            ->whereIn("tiket.status", ["dibayar", "digunakan"])
            ->whereBetween("tiket.waktu_pesan", [$startDate, $endDate])
            ->groupBy("users.id", "users.name", "users.email")
            ->orderByDesc("total_tiket")
            ->limit(10)
            ->get();

        return view("laporan.penumpang", compact("totalPenumpang", "penumpangPerHari", "penumpangPerRute", "topPenumpang", "startDate", "endDate"));
    }
}
