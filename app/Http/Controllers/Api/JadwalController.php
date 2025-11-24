<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    /**
     * Get daftar jadwal dengan filter
     * GET /api/jadwal?asal=&tujuan=&tanggal=
     */
    public function index(Request $request)
    {
        $jadwals = Jadwal::with([
            'bus',
            'sopir.user',
            'rute.asalTerminal',
            'rute.tujuanTerminal',
            'jadwalKelasBuses.kelasBus'
        ])
            ->where('status', 'tersedia')
            ->when($request->asal, fn($q) => $q->whereHas('rute.asalTerminal', fn($q2) => $q2->where('nama_terminal', 'like', '%' . $request->asal . '%')))
            ->when($request->tujuan, fn($q) => $q->whereHas('rute.tujuanTerminal', fn($q2) => $q2->where('nama_terminal', 'like', '%' . $request->tujuan . '%')))
            ->when($request->tanggal, fn($q) => $q->whereDate('tanggal_berangkat', $request->tanggal))
            ->get();

        return response()->json([
            'success' => true,
            'data' => $jadwals->map(function ($jadwal) {
                return [
                    'id' => $jadwal->id,
                    'tanggal_berangkat' => $jadwal->tanggal_berangkat,
                    'waktu_berangkat' => $jadwal->waktu_berangkat,
                    'estimasi_tiba' => $jadwal->estimasi_tiba,
                    'status' => $jadwal->status,
                    'bus' => [
                        'id' => $jadwal->bus->id,
                        'nama' => $jadwal->bus->nama_bus,
                        'plat_nomor' => $jadwal->bus->plat_nomor,
                        'kapasitas' => $jadwal->bus->kapasitas,
                    ],
                    'rute' => [
                        'id' => $jadwal->rute->id,
                        'asal' => $jadwal->rute->asalTerminal->nama_terminal,
                        'tujuan' => $jadwal->rute->tujuanTerminal->nama_terminal,
                    ],
                    'kelas_tersedia' => $jadwal->jadwalKelasBuses->map(fn($jkb) => [
                        'id' => $jkb->id,
                        'kelas_bus_id' => $jkb->kelas_bus_id,
                        'nama_kelas' => $jkb->kelasBus->nama_kelas,
                        'harga' => $jkb->harga,
                    ]),
                ];
            }),
        ]);
    }

    /**
     * Get detail jadwal
     * GET /api/jadwal/{id}
     */
    public function show($id)
    {
        $jadwal = Jadwal::with([
            'bus',
            'sopir.user',
            'rute.asalTerminal',
            'rute.tujuanTerminal',
            'jadwalKelasBuses.kelasBus'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $jadwal->id,
                'tanggal_berangkat' => $jadwal->tanggal_berangkat,
                'waktu_berangkat' => $jadwal->waktu_berangkat,
                'estimasi_tiba' => $jadwal->estimasi_tiba,
                'status' => $jadwal->status,
                'bus' => [
                    'id' => $jadwal->bus->id,
                    'nama' => $jadwal->bus->nama_bus,
                    'plat_nomor' => $jadwal->bus->plat_nomor,
                    'kapasitas' => $jadwal->bus->kapasitas,
                ],
                'sopir' => [
                    'id' => $jadwal->sopir->id,
                    'nama' => $jadwal->sopir->user->name,
                ],
                'rute' => [
                    'id' => $jadwal->rute->id,
                    'asal' => [
                        'id' => $jadwal->rute->asalTerminal->id,
                        'nama' => $jadwal->rute->asalTerminal->nama_terminal,
                        'kota' => $jadwal->rute->asalTerminal->kota,
                    ],
                    'tujuan' => [
                        'id' => $jadwal->rute->tujuanTerminal->id,
                        'nama' => $jadwal->rute->tujuanTerminal->nama_terminal,
                        'kota' => $jadwal->rute->tujuanTerminal->kota,
                    ],
                ],
                'kelas_tersedia' => $jadwal->jadwalKelasBuses->map(fn($jkb) => [
                    'id' => $jkb->id,
                    'kelas_bus_id' => $jkb->kelas_bus_id,
                    'nama_kelas' => $jkb->kelasBus->nama_kelas,
                    'posisi' => $jkb->kelasBus->posisi,
                    'jumlah_kursi' => $jkb->kelasBus->jumlah_kursi,
                    'harga' => $jkb->harga,
                ]),
            ],
        ]);
    }
}
