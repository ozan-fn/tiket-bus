<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\JadwalKelasBus;
use App\Models\Kursi;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CekKursiController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $userRole = $user->roles->first()?->name;

        // Filter jadwal berdasarkan terminal agent dengan pagination
        $jadwals = Jadwal::with("bus", "sopir.user", "conductor.user", "rute.asalTerminal", "rute.tujuanTerminal", "jadwalKelasBus.kelasBus")
            ->when($userRole === "agent" && $user->terminal_id, function ($query) use ($user) {
                return $query->whereHas("rute", function ($q) use ($user) {
                    $q->where("asal_terminal_id", $user->terminal_id);
                });
            })
            ->orderBy("tanggal_berangkat", "desc")
            ->paginate(5);

        return view("cek-kursi.index", compact("jadwals"));
    }

    public function getKursi(Request $request): JsonResponse
    {
        try {
            $jadwalKelasBusId = $request->input("jadwal_kelas_bus_id");

            if (!$jadwalKelasBusId) {
                return response()->json(
                    [
                        "success" => false,
                        "message" => "ID jadwal kelas bus tidak ditemukan",
                    ],
                    400,
                );
            }

            // Get jadwal kelas bus with related data
            $jadwalKelasBus = JadwalKelasBus::with(["jadwal.bus", "jadwal.rute.asalTerminal", "jadwal.rute.tujuanTerminal", "kelasBus.bus", "tikets.kursi"])->find($jadwalKelasBusId);

            if (!$jadwalKelasBus) {
                return response()->json(
                    [
                        "success" => false,
                        "message" => "Data tidak ditemukan",
                    ],
                    404,
                );
            }

            // Get all kursi untuk kelas bus ini
            $kelasBus = $jadwalKelasBus->kelasBus;
            $allKursi = Kursi::where("kelas_bus_id", $kelasBus->id)->orderBy("nomor_kursi", "asc")->get();

            // Get booked tickets
            $bookedKursiIds = $jadwalKelasBus->tikets()->pluck("kursi_id")->toArray();

            // Map kursi dengan status
            $kursiData = $allKursi->map(function ($kursi) use ($bookedKursiIds) {
                return [
                    "id" => $kursi->id,
                    "nomor_kursi" => $kursi->nomor_kursi,
                    "status" => in_array($kursi->id, $bookedKursiIds) ? "booked" : "available",
                ];
            });

            $totalKursi = $allKursi->count();
            $bookedKursi = count($bookedKursiIds);
            $availableKursi = $totalKursi - $bookedKursi;

            return response()->json([
                "success" => true,
                "data" => [
                    "jadwal" => [
                        "tanggal_berangkat" => $jadwalKelasBus->jadwal->tanggal_berangkat->format("d M Y"),
                        "jam_berangkat" => $jadwalKelasBus->jadwal->jam_berangkat->format("H:i"),
                        "bus_nama" => $jadwalKelasBus->jadwal->bus->nama,
                        "bus_plat" => $jadwalKelasBus->jadwal->bus->plat_nomor,
                        "asal_terminal" => $jadwalKelasBus->jadwal->rute->asalTerminal->nama_terminal,
                        "tujuan_terminal" => $jadwalKelasBus->jadwal->rute->tujuanTerminal->nama_terminal,
                    ],
                    "kelas" => [
                        "nama_kelas" => $jadwalKelasBus->kelasBus->nama_kelas,
                        "harga" => $jadwalKelasBus->harga,
                    ],
                    "kursi_summary" => [
                        "total" => $totalKursi,
                        "booked" => $bookedKursi,
                        "available" => $availableKursi,
                    ],
                    "kursi" => $kursiData,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Terjadi kesalahan: " . $e->getMessage(),
                ],
                500,
            );
        }
    }
}
