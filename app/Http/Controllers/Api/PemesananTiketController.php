<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tiket;
use App\Models\Jadwal;
use App\Models\User;

class PemesananTiketController extends Controller
{
    // Endpoint pemesanan tiket
    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwal,id',
            'kelas_bus_id' => 'required|exists:kelas_bus,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        $user = $request->user();
        $jadwal = Jadwal::findOrFail($request->jadwal_id);

        // Cek ketersediaan kursi
        $tersedia = $jadwal->tersedia_kursi($request->kelas_bus_id);
        if ($tersedia < $request->jumlah) {
            return response()->json(['message' => 'Kursi tidak tersedia'], 422);
        }

        // Buat tiket
        $tiket = Tiket::create([
            'user_id' => $user->id,
            'jadwal_id' => $jadwal->id,
            'kelas_bus_id' => $request->kelas_bus_id,
            'jumlah' => $request->jumlah,
            'status_pembayaran' => 'pending',
        ]);

        return response()->json(['message' => 'Pemesanan berhasil', 'tiket' => $tiket], 201);
    }
}