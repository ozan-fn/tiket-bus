<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tiket;
use App\Models\Kursi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TiketController extends Controller
{
    /**
     * Buat pemesanan tiket baru
     * POST /api/tiket
     */
    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwal,id',
            'jadwal_kelas_bus_id' => 'required|exists:jadwal_kelas_bus,id',
            'kursi_id' => 'required|exists:kursi,id',
            'nama_penumpang' => 'required|string|max:255',
            'nik' => 'required|string|max:16',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:L,P',
            'nomor_telepon' => 'required|string|max:20',
            'email' => 'required|email',
        ]);

        // Cek kursi masih tersedia atau tidak
        $kursiTerpakai = Tiket::where('jadwal_id', $request->jadwal_id)
            ->where('kursi_id', $request->kursi_id)
            ->whereIn('status', ['dipesan', 'dibayar'])
            ->exists();

        if ($kursiTerpakai) {
            return response()->json([
                'success' => false,
                'message' => 'Kursi sudah dipesan oleh penumpang lain',
            ], 409);
        }

        // Generate kode tiket unik
        $kodeTicket = 'TKT-' . strtoupper(Str::random(8));

        // Get harga dari jadwal_kelas_bus
        $jadwalKelasBus = \App\Models\JadwalKelasBus::findOrFail($request->jadwal_kelas_bus_id);

        $tiket = Tiket::create([
            'user_id' => auth()->id() ?? 1, // Jika guest bisa pakai ID default
            'jadwal_id' => $request->jadwal_id,
            'jadwal_kelas_bus_id' => $request->jadwal_kelas_bus_id,
            'kursi_id' => $request->kursi_id,
            'nama_penumpang' => $request->nama_penumpang,
            'nik' => $request->nik,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'nomor_telepon' => $request->nomor_telepon,
            'email' => $request->email,
            'kode_tiket' => $kodeTicket,
            'harga' => $jadwalKelasBus->harga,
            'status' => 'dipesan',
            'waktu_pesan' => now(),
        ]);

        $tiket->load(['jadwal.bus', 'jadwal.rute.asalTerminal', 'jadwal.rute.tujuanTerminal', 'kursi']);

        return response()->json([
            'success' => true,
            'message' => 'Tiket berhasil dipesan',
            'data' => [
                'id' => $tiket->id,
                'kode_tiket' => $tiket->kode_tiket,
                'nama_penumpang' => $tiket->nama_penumpang,
                'kursi' => [
                    'nomor' => $tiket->kursi->nomor_kursi,
                    'posisi' => $tiket->kursi->posisi,
                ],
                'harga' => $tiket->harga,
                'status' => $tiket->status,
                'waktu_pesan' => $tiket->waktu_pesan,
            ],
        ], 201);
    }

    /**
     * Get tiket user yang login
     * GET /api/tiket/my-tickets
     */
    public function myTickets()
    {
        $tikets = Tiket::with([
            'jadwal.bus',
            'jadwal.rute.asalTerminal',
            'jadwal.rute.tujuanTerminal',
            'kursi'
        ])
            ->where('user_id', auth()->id())
            ->orderBy('waktu_pesan', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $tikets->map(fn($tiket) => [
                'id' => $tiket->id,
                'kode_tiket' => $tiket->kode_tiket,
                'nama_penumpang' => $tiket->nama_penumpang,
                'rute' => [
                    'asal' => $tiket->jadwal->rute->asalTerminal->nama_terminal,
                    'tujuan' => $tiket->jadwal->rute->tujuanTerminal->nama_terminal,
                ],
                'tanggal_berangkat' => $tiket->jadwal->tanggal_berangkat,
                'waktu_berangkat' => $tiket->jadwal->waktu_berangkat,
                'kursi' => $tiket->kursi->nomor_kursi,
                'harga' => $tiket->harga,
                'status' => $tiket->status,
                'waktu_pesan' => $tiket->waktu_pesan,
            ]),
        ]);
    }

    /**
     * Get detail tiket
     * GET /api/tiket/{kode_tiket}
     */
    public function show($kodeTicket)
    {
        $tiket = Tiket::with([
            'jadwal.bus',
            'jadwal.sopir.user',
            'jadwal.rute.asalTerminal',
            'jadwal.rute.tujuanTerminal',
            'kursi'
        ])
            ->where('kode_tiket', $kodeTicket)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $tiket->id,
                'kode_tiket' => $tiket->kode_tiket,
                'nama_penumpang' => $tiket->nama_penumpang,
                'nik' => $tiket->nik,
                'jenis_kelamin' => $tiket->jenis_kelamin,
                'nomor_telepon' => $tiket->nomor_telepon,
                'email' => $tiket->email,
                'bus' => [
                    'nama' => $tiket->jadwal->bus->nama_bus,
                    'plat_nomor' => $tiket->jadwal->bus->plat_nomor,
                ],
                'rute' => [
                    'asal' => $tiket->jadwal->rute->asalTerminal->nama_terminal,
                    'tujuan' => $tiket->jadwal->rute->tujuanTerminal->nama_terminal,
                ],
                'tanggal_berangkat' => $tiket->jadwal->tanggal_berangkat,
                'waktu_berangkat' => $tiket->jadwal->waktu_berangkat,
                'kursi' => [
                    'nomor' => $tiket->kursi->nomor_kursi,
                    'posisi' => $tiket->kursi->posisi,
                    'dekat_jendela' => $tiket->kursi->dekat_jendela,
                ],
                'harga' => $tiket->harga,
                'status' => $tiket->status,
                'waktu_pesan' => $tiket->waktu_pesan,
            ],
        ]);
    }
}
