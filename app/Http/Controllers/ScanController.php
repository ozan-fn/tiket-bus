<?php

namespace App\Http\Controllers;

use App\Models\Tiket;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function index(): View
    {
        return view("pemesanan.scan");
    }

    public function verifyTicket(Request $request): JsonResponse
    {
        $request->validate([
            "kode_tiket" => "required|string",
        ]);

        $tiket = Tiket::with(["jadwalKelasBus.jadwal.rute.asalTerminal", "jadwalKelasBus.jadwal.rute.tujuanTerminal", "jadwalKelasBus.kelasBus", "user"])
            ->where("kode_tiket", $request->kode_tiket)
            ->first();

        if (!$tiket) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Kode tiket tidak ditemukan",
                ],
                404,
            );
        }

        return response()->json([
            "success" => true,
            "message" => "Tiket ditemukan",
            "data" => [
                "kode_tiket" => $tiket->kode_tiket,
                "nama_penumpang" => $tiket->nama_penumpang,
                "nik" => $tiket->nik,
                "jenis_kelamin" => $tiket->jenis_kelamin,
                "nomor_telepon" => $tiket->nomor_telepon,
                "email" => $tiket->email,
                "status" => $tiket->status,
                "harga" => $tiket->harga,
                "kursi" => $tiket->kursi ? $tiket->kursi->nomor_kursi : "N/A",
                "jadwal" => [
                    "tanggal_berangkat" => $tiket->jadwalKelasBus->jadwal->tanggal_berangkat,
                    "jam_berangkat" => $tiket->jadwalKelasBus->jadwal->jam_berangkat,
                    "asal" => $tiket->jadwalKelasBus->jadwal->rute->asalTerminal->nama_terminal ?? "N/A",
                    "tujuan" => $tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->nama_terminal ?? "N/A",
                ],
                "kelas" => $tiket->jadwalKelasBus->kelasBus->nama_kelas ?? "N/A",
            ],
        ]);
    }
}
