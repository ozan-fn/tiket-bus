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
        $jadwal = Jadwal::with(['bus.kelasBuses.kursis'])->findOrFail($jadwalId);

        // Get semua kursi yang sudah terpakai di jadwal ini
        $kursiTerpakai = Tiket::where('jadwal_id', $jadwalId)
            ->whereIn('status', ['dipesan', 'dibayar'])
            ->pluck('kursi_id')
            ->toArray();

        $result = [];
        foreach ($jadwal->bus->kelasBuses as $kelasBus) {
            $kursis = $kelasBus->kursis->map(function ($kursi) use ($kursiTerpakai) {
                return [
                    'id' => $kursi->id,
                    'nomor_kursi' => $kursi->nomor_kursi,
                    'baris' => $kursi->baris,
                    'kolom' => $kursi->kolom,
                    'posisi' => $kursi->posisi,
                    'dekat_jendela' => $kursi->dekat_jendela,
                    'tersedia' => !in_array($kursi->id, $kursiTerpakai),
                ];
            });

            $result[] = [
                'kelas_bus_id' => $kelasBus->id,
                'nama_kelas' => $kelasBus->nama_kelas,
                'posisi' => $kelasBus->posisi,
                'jumlah_kursi' => $kelasBus->jumlah_kursi,
                'kursi' => $kursis,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'jadwal_id' => $jadwal->id,
                'bus' => [
                    'id' => $jadwal->bus->id,
                    'nama' => $jadwal->bus->nama_bus,
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
        $jadwalId = $request->query('jadwal_id');

        $isAvailable = !Tiket::where('jadwal_id', $jadwalId)
            ->where('kursi_id', $kursiId)
            ->whereIn('status', ['dipesan', 'dibayar'])
            ->exists();

        return response()->json([
            'success' => true,
            'kursi_id' => $kursiId,
            'jadwal_id' => $jadwalId,
            'tersedia' => $isAvailable,
        ]);
    }
}
