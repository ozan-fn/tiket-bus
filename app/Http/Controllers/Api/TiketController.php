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
            'jadwal_kelas_bus_id' => 'required|exists:jadwal_kelas_bus,id',
            'kursi_id' => 'required|exists:kursi,id',
            'nama_penumpang' => 'required|string|max:255',
            'nik' => 'required|string|max:16',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:L,P',
            'nomor_telepon' => 'required|string|max:20',
            'email' => 'required|email',
        ]);

        // Get jadwal dari jadwal_kelas_bus
        $jadwalKelasBus = \App\Models\JadwalKelasBus::with('jadwal')->findOrFail($request->jadwal_kelas_bus_id);
        $jadwalId = $jadwalKelasBus->jadwal_id;

        // Cek kursi masih tersedia atau tidak
        $kursiTerpakai = Tiket::where('jadwal_kelas_bus_id', $request->jadwal_kelas_bus_id)
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

        $tiket = Tiket::create([
            'user_id' => auth()->id() ?? 1, // Jika guest bisa pakai ID default
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

        $tiket->load(['jadwalKelasBus.jadwal.bus', 'jadwalKelasBus.jadwal.rute.asalTerminal', 'jadwalKelasBus.jadwal.rute.tujuanTerminal', 'kursi']);

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
            'jadwalKelasBus.jadwal.bus',
            'jadwalKelasBus.jadwal.rute.asalTerminal',
            'jadwalKelasBus.jadwal.rute.tujuanTerminal',
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
                    'asal' => $tiket->jadwalKelasBus->jadwal->rute->asalTerminal->nama_terminal,
                    'tujuan' => $tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->nama_terminal,
                ],
                'tanggal_berangkat' => $tiket->jadwalKelasBus->jadwal->tanggal_berangkat,
                'jam_berangkat' => $tiket->jadwalKelasBus->jadwal->jam_berangkat,
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
            'jadwalKelasBus.jadwal.bus',
            'jadwalKelasBus.jadwal.sopir.user',
            'jadwalKelasBus.jadwal.rute.asalTerminal',
            'jadwalKelasBus.jadwal.rute.tujuanTerminal',
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
                    'nama' => $tiket->jadwalKelasBus->jadwal->bus->nama,
                    'plat_nomor' => $tiket->jadwalKelasBus->jadwal->bus->plat_nomor,
                ],
                'rute' => [
                    'asal' => $tiket->jadwalKelasBus->jadwal->rute->asalTerminal->nama_terminal,
                    'tujuan' => $tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->nama_terminal,
                ],
                'tanggal_berangkat' => $tiket->jadwalKelasBus->jadwal->tanggal_berangkat,
                'jam_berangkat' => $tiket->jadwalKelasBus->jadwal->jam_berangkat,
                'kursi' => [
                    'nomor' => $tiket->kursi->nomor_kursi,
                    'index' => $tiket->kursi->index,
                ],
                'harga' => $tiket->harga,
                'status' => $tiket->status,
                'waktu_pesan' => $tiket->waktu_pesan,
            ],
        ]);
    }

    /**
     * Verifikasi tiket oleh petugas (scan QR/input kode)
     * POST /api/tiket/{kode_tiket}/verify
     */
    public function verify($kodeTicket)
    {
        $tiket = Tiket::with([
            'jadwalKelasBus.jadwal.bus',
            'jadwalKelasBus.jadwal.rute.asalTerminal',
            'jadwalKelasBus.jadwal.rute.tujuanTerminal',
            'jadwalKelasBus.kelasBus',
            'kursi',
            'pembayaran'
        ])
            ->where('kode_tiket', $kodeTicket)
            ->firstOrFail();

        // Cek apakah tiket sudah dibayar
        if ($tiket->status !== 'dibayar') {
            return response()->json([
                'success' => false,
                'message' => 'Tiket belum dibayar atau sudah dibatalkan',
                'data' => [
                    'kode_tiket' => $tiket->kode_tiket,
                    'status' => $tiket->status,
                ],
            ], 403);
        }

        // Cek apakah tiket sudah digunakan (naik bus)
        if ($tiket->status === 'selesai') {
            return response()->json([
                'success' => false,
                'message' => 'Tiket sudah pernah digunakan',
                'data' => [
                    'kode_tiket' => $tiket->kode_tiket,
                    'status' => $tiket->status,
                    'waktu_digunakan' => $tiket->updated_at,
                ],
            ], 409);
        }

        // Return data lengkap untuk petugas
        return response()->json([
            'success' => true,
            'message' => 'Tiket valid',
            'data' => [
                'kode_tiket' => $tiket->kode_tiket,
                'status' => $tiket->status,
                'penumpang' => [
                    'nama' => $tiket->nama_penumpang,
                    'nik' => $tiket->nik,
                    'jenis_kelamin' => $tiket->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
                    'nomor_telepon' => $tiket->nomor_telepon,
                ],
                'jadwal' => [
                    'tanggal_berangkat' => $tiket->jadwalKelasBus->jadwal->tanggal_berangkat,
                    'jam_berangkat' => $tiket->jadwalKelasBus->jadwal->jam_berangkat,
                ],
                'bus' => [
                    'nama' => $tiket->jadwalKelasBus->jadwal->bus->nama,
                    'plat_nomor' => $tiket->jadwalKelasBus->jadwal->bus->plat_nomor,
                ],
                'rute' => [
                    'asal' => $tiket->jadwalKelasBus->jadwal->rute->asalTerminal->nama_terminal,
                    'tujuan' => $tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->nama_terminal,
                ],
                'kelas' => $tiket->jadwalKelasBus->kelasBus->nama_kelas,
                'kursi' => [
                    'nomor' => $tiket->kursi->nomor_kursi,
                    'posisi' => $tiket->kursi->posisi,
                ],
                'harga' => $tiket->harga,
                'pembayaran' => [
                    'metode' => $tiket->pembayaran->metode ?? null,
                    'waktu_bayar' => $tiket->pembayaran->waktu_bayar ?? null,
                ],
            ],
        ]);
    }

    /**
     * Tandai tiket sebagai digunakan (penumpang naik)
     * POST /api/tiket/{kode_tiket}/checkin
     */
    public function checkin($kodeTicket)
    {
        $tiket = Tiket::where('kode_tiket', $kodeTicket)->firstOrFail();

        if ($tiket->status !== 'dibayar') {
            return response()->json([
                'success' => false,
                'message' => 'Tiket belum dibayar atau tidak valid',
            ], 403);
        }

        if ($tiket->status === 'selesai') {
            return response()->json([
                'success' => false,
                'message' => 'Tiket sudah pernah digunakan',
            ], 409);
        }

        $tiket->update(['status' => 'selesai']);

        return response()->json([
            'success' => true,
            'message' => 'Check-in berhasil, penumpang boleh naik',
            'data' => [
                'kode_tiket' => $tiket->kode_tiket,
                'nama_penumpang' => $tiket->nama_penumpang,
                'status' => $tiket->status,
                'waktu_checkin' => now(),
            ],
        ]);
    }
}
