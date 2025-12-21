<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tiket;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Laporan tiket
     * GET /api/laporan/tiket?dari=&sampai=&status=
     */
    public function tiket(Request $request)
    {
        $query = Tiket::with(["jadwalKelasBus.jadwal.bus", "jadwalKelasBus.jadwal.rute", "user"]);

        if ($request->dari && $request->sampai) {
            $query->whereBetween("waktu_pesan", [$request->dari, $request->sampai]);
        }

        if ($request->status) {
            $query->where("status", $request->status);
        }

        $tikets = $query->orderBy("waktu_pesan", "desc")->get();

        $summary = [
            "total_tiket" => $tikets->count(),
            "tiket_dipesan" => $tikets->where("status", "dipesan")->count(),
            "tiket_dibayar" => $tikets->where("status", "dibayar")->count(),
            "tiket_batal" => $tikets->where("status", "batal")->count(),
            "total_pendapatan" => $tikets->whereIn("status", ["dibayar", "selesai"])->sum("harga"),
        ];

        return response()->json([
            "success" => true,
            "summary" => $summary,
            "data" => $tikets->map(
                fn($t) => [
                    "id" => $t->id,
                    "kode_tiket" => $t->kode_tiket,
                    "nama_penumpang" => $t->nama_penumpang,
                    "bus" => $t->jadwalKelasBus->jadwal->bus->nama,
                    "rute" => $t->jadwalKelasBus->jadwal->rute->asalTerminal->nama_terminal . " - " . $t->jadwalKelasBus->jadwal->rute->tujuanTerminal->nama_terminal,
                    "harga" => $t->harga,
                    "status" => $t->status,
                    "waktu_pesan" => $t->waktu_pesan,
                ],
            ),
        ]);
    }

    /**
     * Laporan pendapatan
     * GET /api/laporan/pendapatan?dari=&sampai=&group_by=daily|monthly
     */
    public function pendapatan(Request $request)
    {
        $groupBy = $request->group_by ?? "daily";

        $query = Pembayaran::where("status", "dibayar");

        if ($request->dari && $request->sampai) {
            $query->whereBetween("waktu_bayar", [$request->dari, $request->sampai]);
        }

        if ($groupBy === "monthly") {
            $data = $query->select(DB::raw('DATE_FORMAT(waktu_bayar, "%Y-%m") as periode'), DB::raw("SUM(nominal) as total"), DB::raw("COUNT(*) as jumlah_transaksi"))->groupBy("periode")->orderBy("periode", "desc")->get();
        } else {
            $data = $query->select(DB::raw("DATE(waktu_bayar) as periode"), DB::raw("SUM(nominal) as total"), DB::raw("COUNT(*) as jumlah_transaksi"))->groupBy("periode")->orderBy("periode", "desc")->get();
        }

        $summary = [
            "total_pendapatan" => $data->sum("total"),
            "total_transaksi" => $data->sum("jumlah_transaksi"),
        ];

        return response()->json([
            "success" => true,
            "summary" => $summary,
            "data" => $data,
        ]);
    }

    /**
     * Laporan penumpang
     * GET /api/laporan/penumpang?dari=&sampai=
     */
    public function penumpang(Request $request)
    {
        $query = Tiket::with(["jadwalKelasBus.jadwal.bus", "jadwalKelasBus.jadwal.rute"])->whereIn("status", ["dibayar", "selesai"]);

        if ($request->dari && $request->sampai) {
            $query->whereHas("jadwalKelasBus.jadwal", function ($q) use ($request) {
                $q->whereBetween("tanggal_berangkat", [$request->dari, $request->sampai]);
            });
        }

        // Group by bus
        $perBus = $query
            ->get()
            ->groupBy(function ($tiket) {
                return $tiket->jadwalKelasBus->jadwal->bus->nama;
            })
            ->map(function ($group, $busName) {
                return [
                    "bus" => $busName,
                    "total_penumpang" => $group->count(),
                    "total_pendapatan" => $group->sum("harga"),
                ];
            })
            ->values();

        // Group by rute
        $perRute = $query
            ->get()
            ->groupBy(function ($tiket) {
                $rute = $tiket->jadwalKelasBus->jadwal->rute;
                return $rute->asalTerminal->nama_terminal . " - " . $rute->tujuanTerminal->nama_terminal;
            })
            ->map(function ($group, $ruteName) {
                return [
                    "rute" => $ruteName,
                    "total_penumpang" => $group->count(),
                    "total_pendapatan" => $group->sum("harga"),
                ];
            })
            ->values();

        return response()->json([
            "success" => true,
            "per_bus" => $perBus,
            "per_rute" => $perRute,
        ]);
    }
}
