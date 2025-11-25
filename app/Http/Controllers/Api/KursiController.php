<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\KelasBus;
use App\Models\Tiket;
use Illuminate\Http\Request;

class KursiController extends Controller
{
    /**
     * Get kursi layout untuk jadwal_kelas_bus tertentu
     * GET /api/jadwal/{jadwal_id}/kursi?jadwal_kelas_bus_id={id}
     */
    public function getKursiByJadwal($jadwalId, Request $request)
    {
        $jadwalKelasBusId = $request->query('jadwal_kelas_bus_id');

        if (!$jadwalKelasBusId) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter jadwal_kelas_bus_id wajib diisi',
            ], 400);
        }

        $jadwalKelasBus = \App\Models\JadwalKelasBus::with([
            'jadwal.bus',
            'kelasBus.kursi'
        ])->findOrFail($jadwalKelasBusId);

        // Pastikan jadwal_id sesuai
        if ($jadwalKelasBus->jadwal_id != $jadwalId) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal kelas bus tidak sesuai dengan jadwal',
            ], 400);
        }

        // Get kursi yang sudah terpakai untuk jadwal_kelas_bus ini
        $kursiTerpakai = Tiket::where('jadwal_kelas_bus_id', $jadwalKelasBusId)
            ->whereIn('status', ['dipesan', 'dibayar'])
            ->pluck('kursi_id')
            ->toArray();

        $kursiList = $jadwalKelasBus->kelasBus->kursi->map(function ($kursi) use ($kursiTerpakai) {
            return [
                'id' => $kursi->id,
                'nomor_kursi' => $kursi->nomor_kursi,
                'posisi' => $kursi->posisi,
                'index' => $kursi->index,
                'tersedia' => !in_array($kursi->id, $kursiTerpakai),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'jadwal_id' => $jadwalKelasBus->jadwal_id,
                'jadwal_kelas_bus_id' => $jadwalKelasBus->id,
                'bus' => [
                    'id' => $jadwalKelasBus->jadwal->bus->id,
                    'nama' => $jadwalKelasBus->jadwal->bus->nama,
                    'plat_nomor' => $jadwalKelasBus->jadwal->bus->plat_nomor,
                ],
                'kelas_bus' => [
                    'id' => $jadwalKelasBus->kelasBus->id,
                    'nama_kelas' => $jadwalKelasBus->kelasBus->nama_kelas,
                    'deskripsi' => $jadwalKelasBus->kelasBus->deskripsi,
                    'jumlah_kursi' => $jadwalKelasBus->kelasBus->jumlah_kursi,
                ],
                'harga' => $jadwalKelasBus->harga,
                'kursi' => $kursiList,
            ],
        ]);
    }

    /**
     * Cek ketersediaan kursi spesifik
     * GET /api/kursi/{kursi_id}/check?jadwal_id={jadwal_id}
     */
    public function checkKetersediaan($kursiId, Request $request)
    {
        $jadwalKelasBusId = $request->query('jadwal_kelas_bus_id');

        $isAvailable = !Tiket::where('jadwal_kelas_bus_id', $jadwalKelasBusId)
            ->where('kursi_id', $kursiId)
            ->whereIn('status', ['dipesan', 'dibayar'])
            ->exists();

        return response()->json([
            'success' => true,
            'kursi_id' => $kursiId,
            'jadwal_kelas_bus_id' => $jadwalKelasBusId,
            'tersedia' => $isAvailable,
        ]);
    }
}
