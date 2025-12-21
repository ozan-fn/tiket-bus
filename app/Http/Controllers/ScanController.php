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
        return view("admin.scan.index");
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
                    "data" => [
                        "kode_tiket" => $request->kode_tiket,
                    ],
                ],
                404,
            );
        }

        // Cek status tiket
        if ($tiket->status === "batal") {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Tiket telah dibatalkan",
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
                            "tanggal_berangkat" => $tiket->jadwalKelasBus?->jadwal?->tanggal_berangkat ?? "N/A",
                            "jam_berangkat" => $tiket->jadwalKelasBus?->jadwal?->jam_berangkat ?? "N/A",
                            "asal" => $tiket->jadwalKelasBus?->jadwal?->rute?->asalTerminal?->nama_terminal ?? "N/A",
                            "tujuan" => $tiket->jadwalKelasBus?->jadwal?->rute?->tujuanTerminal?->nama_terminal ?? "N/A",
                        ],
                        "kelas" => $tiket->jadwalKelasBus?->kelasBus?->nama_kelas ?? "N/A",
                    ],
                ],
                400,
            );
        }

        if ($tiket->status === "dipesan") {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Tiket belum dibayar",
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
                            "tanggal_berangkat" => $tiket->jadwalKelasBus?->jadwal?->tanggal_berangkat ?? "N/A",
                            "jam_berangkat" => $tiket->jadwalKelasBus?->jadwal?->jam_berangkat ?? "N/A",
                            "asal" => $tiket->jadwalKelasBus?->jadwal?->rute?->asalTerminal?->nama_terminal ?? "N/A",
                            "tujuan" => $tiket->jadwalKelasBus?->jadwal?->rute?->tujuanTerminal?->nama_terminal ?? "N/A",
                        ],
                        "kelas" => $tiket->jadwalKelasBus?->kelasBus?->nama_kelas ?? "N/A",
                    ],
                ],
                400,
            );
        }

        // Cek jika tiket expired
        $jadwal = $tiket->jadwalKelasBus?->jadwal;
        if ($jadwal) {
            $waktuBerangkat = \Carbon\Carbon::parse($jadwal->tanggal_berangkat->format("Y-m-d") . " " . $jadwal->jam_berangkat->format("H:i:s"));

            if ($waktuBerangkat->isPast()) {
                return response()->json(
                    [
                        "success" => false,
                        "message" => "Tiket telah expired",
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
                                "tanggal_berangkat" => $jadwal->tanggal_berangkat,
                                "jam_berangkat" => $jadwal->jam_berangkat,
                                "asal" => $jadwal->rute?->asalTerminal?->nama_terminal ?? "N/A",
                                "tujuan" => $jadwal->rute?->tujuanTerminal?->nama_terminal ?? "N/A",
                            ],
                            "kelas" => $tiket->jadwalKelasBus?->kelasBus?->nama_kelas ?? "N/A",
                        ],
                    ],
                    400,
                );
            }
        }

        // Jika status selesai atau dibayar, valid
        return response()->json([
            "success" => true,
            "message" => "Tiket valid",
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
                    "tanggal_berangkat" => $tiket->jadwalKelasBus?->jadwal?->tanggal_berangkat ?? "N/A",
                    "jam_berangkat" => $tiket->jadwalKelasBus?->jadwal?->jam_berangkat ?? "N/A",
                    "asal" => $tiket->jadwalKelasBus?->jadwal?->rute?->asalTerminal?->nama_terminal ?? "N/A",
                    "tujuan" => $tiket->jadwalKelasBus?->jadwal?->rute?->tujuanTerminal?->nama_terminal ?? "N/A",
                ],
                "kelas" => $tiket->jadwalKelasBus?->kelasBus?->nama_kelas ?? "N/A",
            ],
        ]);
    }
}
