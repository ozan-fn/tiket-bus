<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    /**
     * Get daftar jadwal dengan filter dan pagination
     * GET /api/jadwal?asal=&tujuan=&tanggal=&page=&per_page=
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $jadwals = Jadwal::with([
            'bus',
            'sopir.user',
            'rute.asalTerminal',
            'rute.tujuanTerminal',
            'jadwalKelasBus.kelasBus'
        ])
            ->where('status', 'tersedia')
            ->when($request->asal, fn($q) => $q->whereHas('rute.asalTerminal', fn($q2) => $q2->where('nama_terminal', 'like', '%' . $request->asal . '%')))
            ->when($request->tujuan, fn($q) => $q->whereHas('rute.tujuanTerminal', fn($q2) => $q2->where('nama_terminal', 'like', '%' . $request->tujuan . '%')))
            ->when($request->tanggal, fn($q) => $q->whereDate('tanggal_berangkat', $request->tanggal))
            ->orderBy('tanggal_berangkat', 'asc')
            ->orderBy('jam_berangkat', 'asc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $jadwals->map(function ($jadwal) {
                return [
                    'id' => $jadwal->id,
                    'tanggal_berangkat' => $jadwal->tanggal_berangkat,
                    'jam_berangkat' => $jadwal->jam_berangkat,
                    'status' => $jadwal->status,
                    'bus' => [
                        'id' => $jadwal->bus->id,
                        'nama' => $jadwal->bus->nama,
                        'plat_nomor' => $jadwal->bus->plat_nomor,
                        'kapasitas' => $jadwal->bus->kapasitas,
                    ],
                    'rute' => [
                        'id' => $jadwal->rute->id,
                        'asal' => $jadwal->rute->asalTerminal->nama_terminal,
                        'tujuan' => $jadwal->rute->tujuanTerminal->nama_terminal,
                    ],
                    'kelas_tersedia' => $jadwal->jadwalKelasBus->map(fn($jkb) => [
                        'id' => $jkb->id,
                        'kelas_bus_id' => $jkb->kelas_bus_id,
                        'nama_kelas' => $jkb->kelasBus->nama_kelas,
                        'harga' => $jkb->harga,
                    ]),
                ];
            }),
            'pagination' => [
                'total' => $jadwals->total(),
                'per_page' => $jadwals->perPage(),
                'current_page' => $jadwals->currentPage(),
                'last_page' => $jadwals->lastPage(),
                'from' => $jadwals->firstItem(),
                'to' => $jadwals->lastItem(),
            ],
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
            'jadwalKelasBus.kelasBus'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $jadwal->id,
                'tanggal_berangkat' => $jadwal->tanggal_berangkat,
                'jam_berangkat' => $jadwal->jam_berangkat,
                'status' => $jadwal->status,
                'bus' => [
                    'id' => $jadwal->bus->id,
                    'nama' => $jadwal->bus->nama,
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
                        'kota' => $jadwal->rute->asalTerminal->nama_kota,
                    ],
                    'tujuan' => [
                        'id' => $jadwal->rute->tujuanTerminal->id,
                        'nama' => $jadwal->rute->tujuanTerminal->nama_terminal,
                        'kota' => $jadwal->rute->tujuanTerminal->nama_kota,
                    ],
                ],
                'kelas_tersedia' => $jadwal->jadwalKelasBus->map(fn($jkb) => [
                    'id' => $jkb->id,
                    'kelas_bus_id' => $jkb->kelas_bus_id,
                    'nama_kelas' => $jkb->kelasBus->nama_kelas,
                    'deskripsi' => $jkb->kelasBus->deskripsi,
                    'jumlah_kursi' => $jkb->kelasBus->jumlah_kursi,
                    'harga' => $jkb->harga,
                ]),
            ],
        ]);
    }

    /**
     * Buat jadwal baru
     * POST /api/jadwal
     */
    public function store(Request $request)
    {
        $request->validate([
            'bus_id' => 'required|exists:bus,id',
            'sopir_id' => 'required|exists:sopir,id',
            'rute_id' => 'required|exists:rute,id',
            'tanggal_berangkat' => 'required|date',
            'jam_berangkat' => 'required|date_format:H:i',
            'status' => 'required|in:tersedia,berangkat,selesai,batal',
        ]);

        $jadwal = Jadwal::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil dibuat',
            'data' => $jadwal,
        ], 201);
    }

    /**
     * Update jadwal
     * PUT /api/jadwal/{id}
     */
    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $request->validate([
            'bus_id' => 'sometimes|exists:bus,id',
            'sopir_id' => 'sometimes|exists:sopir,id',
            'rute_id' => 'sometimes|exists:rute,id',
            'tanggal_berangkat' => 'sometimes|date',
            'jam_berangkat' => 'sometimes|date_format:H:i',
            'status' => 'sometimes|in:tersedia,berangkat,selesai,batal',
        ]);

        $jadwal->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil diupdate',
            'data' => $jadwal,
        ]);
    }

    /**
     * Hapus jadwal
     * DELETE /api/jadwal/{id}
     */
    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil dihapus',
        ]);
    }
}
