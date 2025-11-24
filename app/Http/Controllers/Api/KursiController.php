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
     * Get kursi layout per kelas bus dalam jadwal tertentu
     * GET /api/jadwal/{jadwal_id}/kursi
     */
    public function getKursiByJadwal($jadwalId)
    {
        $jadwal = Jadwal::with(['bus.kelasBus.kursi', 'jadwalKelasBus'])->findOrFail($jadwalId);

        // Get semua kursi yang sudah terpakai di jadwal ini (via jadwal_kelas_bus)
        $kursiTerpakai = Tiket::whereHas('jadwalKelasBus', function ($q) use ($jadwalId) {
            $q->where('jadwal_id', $jadwalId);
        })
            ->whereIn('status', ['dipesan', 'dibayar'])
            ->pluck('kursi_id')
            ->toArray();

        $result = [];
        foreach ($jadwal->bus->kelasBus as $kelasBus) {
            $kursis = $kelasBus->kursi->map(function ($kursi) use ($kursiTerpakai) {
                return [
                    'id' => $kursi->id,
                    'nomor_kursi' => $kursi->nomor_kursi,
                    'index' => $kursi->index,
                    'tersedia' => !in_array($kursi->id, $kursiTerpakai),
                ];
            });

            // Get harga dari jadwal_kelas_bus
            $jadwalKelas = $jadwal->jadwalKelasBus->firstWhere('kelas_bus_id', $kelasBus->id);

            $result[] = [
                'kelas_bus_id' => $kelasBus->id,
                'nama_kelas' => $kelasBus->nama_kelas,
                'deskripsi' => $kelasBus->deskripsi,
                'jumlah_kursi' => $kelasBus->jumlah_kursi,
                'harga' => $jadwalKelas ? $jadwalKelas->harga : null,
                'kursi' => $kursis,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'jadwal_id' => $jadwal->id,
                'bus' => [
                    'id' => $jadwal->bus->id,
                    'nama' => $jadwal->bus->nama,
                ],
                'kelas_bus' => $result,
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
