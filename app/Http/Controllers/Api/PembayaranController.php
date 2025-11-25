<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Tiket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PembayaranController extends Controller
{
    /**
     * Buat pembayaran untuk tiket
     * POST /api/pembayaran
     */
    public function store(Request $request)
    {
        $request->validate([
            'tiket_id' => 'required|exists:tiket,id',
            'metode' => 'required|in:midtrans,transfer,tunai',
        ]);

        $tiket = Tiket::findOrFail($request->tiket_id);

        // Cek apakah tiket sudah dibayar
        if ($tiket->status === 'dibayar') {
            return response()->json([
                'success' => false,
                'message' => 'Tiket sudah dibayar',
            ], 400);
        }

        // Cek apakah tiket batal
        if ($tiket->status === 'batal') {
            return response()->json([
                'success' => false,
                'message' => 'Tiket sudah dibatalkan',
            ], 400);
        }

        // Idempotensi: jika sudah ada pembayaran pending/berhasil untuk tiket ini, kembalikan
        $existing = Pembayaran::where('tiket_id', $tiket->id)
            ->whereIn('status', ['pending', 'berhasil'])
            ->latest()
            ->first();

        if ($existing) {
            if ($existing->status === 'berhasil') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tiket sudah memiliki pembayaran berhasil',
                ], 409);
            }

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran sudah dibuat sebelumnya',
                'data' => [
                    'id' => $existing->id,
                    'kode_transaksi' => $existing->kode_transaksi,
                    'metode' => $existing->metode,
                    'nominal' => $existing->nominal,
                    'status' => $existing->status,
                    'waktu_bayar' => $existing->waktu_bayar,
                ],
            ], 200);
        }

        // Generate kode transaksi
        $kodeTransaksi = 'TRX-' . strtoupper(Str::random(12));

        $pembayaran = Pembayaran::create([
            'user_id' => auth()->id() ?? $tiket->user_id,
            'tiket_id' => $tiket->id,
            'metode' => $request->metode,
            'nominal' => $tiket->harga,
            'status' => 'pending',
            'kode_transaksi' => $kodeTransaksi,
        ]);

        // Catatan: semua metode pembayaran dimulai dengan 'pending'.
        // Perubahan ke 'berhasil' dilakukan via callback/payment gateway atau verifikasi admin.

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil dibuat',
            'data' => [
                'id' => $pembayaran->id,
                'kode_transaksi' => $pembayaran->kode_transaksi,
                'metode' => $pembayaran->metode,
                'nominal' => $pembayaran->nominal,
                'status' => $pembayaran->status,
                'waktu_bayar' => $pembayaran->waktu_bayar,
            ],
        ], 201);
    }

    /**
     * Get riwayat pembayaran user
     * GET /api/pembayaran
     */
    public function index()
    {
        $pembayarans = Pembayaran::with(['tiket.jadwalKelasBus.jadwal.rute'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pembayarans->map(fn($pembayaran) => [
                'id' => $pembayaran->id,
                'kode_transaksi' => $pembayaran->kode_transaksi,
                'kode_tiket' => $pembayaran->tiket->kode_tiket,
                'metode' => $pembayaran->metode,
                'nominal' => $pembayaran->nominal,
                'status' => $pembayaran->status,
                'waktu_bayar' => $pembayaran->waktu_bayar,
                'created_at' => $pembayaran->created_at,
            ]),
        ]);
    }

    /**
     * Get detail pembayaran
     * GET /api/pembayaran/{id}
     */
    public function show($id)
    {
        $pembayaran = Pembayaran::with(['tiket.jadwalKelasBus.jadwal', 'tiket.kursi', 'user'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $pembayaran->id,
                'kode_transaksi' => $pembayaran->kode_transaksi,
                'tiket' => [
                    'kode_tiket' => $pembayaran->tiket->kode_tiket,
                    'nama_penumpang' => $pembayaran->tiket->nama_penumpang,
                    'kursi' => $pembayaran->tiket->kursi->nomor_kursi,
                ],
                'metode' => $pembayaran->metode,
                'nominal' => $pembayaran->nominal,
                'status' => $pembayaran->status,
                'waktu_bayar' => $pembayaran->waktu_bayar,
                'created_at' => $pembayaran->created_at,
            ],
        ]);
    }

    /**
     * Callback dari payment gateway (Midtrans, dll)
     * POST /api/pembayaran/callback
     */
    public function callback(Request $request)
    {
        // Logic untuk handle callback dari payment gateway
        // Contoh: verifikasi signature, update status pembayaran, dll

        $kodeTransaksi = $request->order_id;
        $status = $request->transaction_status;

        $pembayaran = Pembayaran::where('kode_transaksi', $kodeTransaksi)->first();

        if (!$pembayaran) {
            return response()->json(['success' => false, 'message' => 'Pembayaran tidak ditemukan'], 404);
        }

        // Update status berdasarkan response payment gateway (idempotent)
        if ($status === 'settlement' || $status === 'capture') {
            if ($pembayaran->status !== 'berhasil') {
                $pembayaran->update([
                    'status' => 'berhasil',
                    'waktu_bayar' => now(),
                ]);

                // Terbitkan tiket (ubah status menjadi dibayar)
                $pembayaran->tiket->update(['status' => 'dibayar']);
            }
        } elseif ($status === 'pending') {
            if ($pembayaran->status !== 'berhasil') {
                $pembayaran->update(['status' => 'pending']);
            }
        } elseif (in_array($status, ['deny', 'expire', 'cancel'])) {
            if ($pembayaran->status !== 'berhasil') {
                $pembayaran->update(['status' => 'gagal']);
            }
        }

        return response()->json(['success' => true, 'message' => 'Callback processed']);
    }

    /**
     * Approve pembayaran manual (transfer/tunai) oleh admin/agent
     * POST /api/pembayaran/{id}/approve
     */
    public function approveManual($id)
    {
        $pembayaran = Pembayaran::with('tiket')->findOrFail($id);

        if (!in_array($pembayaran->metode, ['transfer', 'tunai'])) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pembayaran manual yang dapat di-approve',
            ], 422);
        }

        if ($pembayaran->status === 'berhasil') {
            return response()->json([
                'success' => true,
                'message' => 'Pembayaran sudah berhasil sebelumnya',
            ], 200);
        }

        if ($pembayaran->status === 'gagal') {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran sudah ditandai gagal',
            ], 409);
        }

        $pembayaran->update([
            'status' => 'berhasil',
            'waktu_bayar' => now(),
        ]);

        if ($pembayaran->tiket && $pembayaran->tiket->status !== 'dibayar') {
            $pembayaran->tiket->update(['status' => 'dibayar']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran manual berhasil di-approve dan tiket diterbitkan',
        ]);
    }

    /**
     * Reject pembayaran manual (transfer/tunai) oleh admin/agent
     * POST /api/pembayaran/{id}/reject
     */
    public function rejectManual($id)
    {
        $pembayaran = Pembayaran::with('tiket')->findOrFail($id);

        if (!in_array($pembayaran->metode, ['transfer', 'tunai'])) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pembayaran manual yang dapat di-reject',
            ], 422);
        }

        if ($pembayaran->status === 'berhasil') {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menolak pembayaran yang sudah berhasil',
            ], 409);
        }

        if ($pembayaran->status !== 'gagal') {
            $pembayaran->update(['status' => 'gagal']);
        }

        // Release kursi dengan menandai tiket batal
        if ($pembayaran->tiket && $pembayaran->tiket->status !== 'batal') {
            $pembayaran->tiket->update(['status' => 'batal']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran manual ditolak dan kursi dirilis',
        ]);
    }
}
