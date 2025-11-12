<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Tiket;
use Illuminate\Support\Facades\DB;

class PembayaranTiketController extends Controller
{
    // Endpoint pembayaran tiket
    public function store(Request $request)
    {
        $request->validate([
            'tiket_id' => 'required|exists:tiket,id',
            'metode' => 'required|string',
            'jumlah_bayar' => 'required|numeric|min:0',
        ]);

        $tiket = Tiket::findOrFail($request->tiket_id);
        if ($tiket->status_pembayaran === 'paid') {
            return response()->json(['message' => 'Tiket sudah dibayar'], 422);
        }

        DB::beginTransaction();
        try {
            $pembayaran = Pembayaran::create([
                'tiket_id' => $tiket->id,
                'user_id' => $request->user()->id,
                'metode' => $request->metode,
                'jumlah_bayar' => $request->jumlah_bayar,
                'status' => 'success',
            ]);
            $tiket->update(['status_pembayaran' => 'paid']);
            DB::commit();
            return response()->json(['message' => 'Pembayaran berhasil', 'pembayaran' => $pembayaran], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Pembayaran gagal', 'error' => $e->getMessage()], 500);
        }
    }
}
