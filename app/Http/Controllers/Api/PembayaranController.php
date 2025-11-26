<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Tiket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

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
            'metode' => 'required|in:xendit,transfer,tunai',
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

        // Handle Xendit payment intent creation (API v3)
        if ($request->metode === 'xendit') {
            $xenditResponse = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode(config('services.xendit.api_key') . ':'),
                'Content-Type' => 'application/json',
                'xendit-api-version' => '2022-07-31',
            ])->post(config('services.xendit.base_url') . '/v2/invoices', [
                        'external_id' => $kodeTransaksi,
                        'amount' => $tiket->harga,
                        'currency' => 'IDR',
                        'description' => 'Pembayaran Tiket Bus - ' . $tiket->kode_tiket,
                        'invoice_duration' => 86400, // 24 hours
                        'customer' => [
                            'given_names' => $tiket->nama_penumpang,
                            'email' => $tiket->email,
                            'mobile_number' => $tiket->nomor_telepon,
                        ],
                        'customer_notification_preference' => [
                            'invoice_created' => ['email', 'whatsapp'],
                            'invoice_reminder' => ['email'],
                            'invoice_paid' => ['email', 'whatsapp'],
                        ],
                        'success_redirect_url' => $request->input('success_redirect_url', env('APP_URL') . '/payment/success'),
                        'failure_redirect_url' => $request->input('failure_redirect_url', env('APP_URL') . '/payment/failure'),
                        'payment_methods' => ['CREDIT_CARD', 'BANK_TRANSFER', 'EWALLET', 'QR_CODE', 'RETAIL_OUTLET'],
                        'items' => [
                            [
                                'name' => 'Tiket Bus - ' . $tiket->kode_tiket,
                                'quantity' => 1,
                                'price' => $tiket->harga,
                                'category' => 'Transportation',
                            ]
                        ],
                    ]);

            if ($xenditResponse->failed()) {
                $pembayaran->update(['status' => 'gagal']);
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat payment intent Xendit',
                    'error' => $xenditResponse->json(),
                ], 500);
            }

            $invoiceData = $xenditResponse->json();
            $pembayaran->update(['external_id' => $invoiceData['id'] ?? null]);
        }

        // Catatan: semua metode pembayaran dimulai dengan 'pending'.
        // Perubahan ke 'berhasil' dilakukan via callback/payment gateway atau verifikasi admin.

        // Siapkan response
        $responseData = [
            'id' => $pembayaran->id,
            'kode_transaksi' => $pembayaran->kode_transaksi,
            'metode' => $pembayaran->metode,
            'nominal' => $pembayaran->nominal,
            'status' => $pembayaran->status,
            'waktu_bayar' => $pembayaran->waktu_bayar,
        ];

        if (isset($invoiceData)) {
            $responseData['invoice_url'] = $invoiceData['invoice_url'] ?? null;
            $responseData['expiry_date'] = $invoiceData['expiry_date'] ?? null;
            $responseData['external_id'] = $invoiceData['id'] ?? null;
        }

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil dibuat',
            'data' => $responseData,
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
     * Callback dari Xendit
     * POST /api/pembayaran/callback
     */
    public function callback(Request $request)
    {
        // Optional: verifikasi callback token
        $callbackToken = config('services.xendit.callback_token');
        if ($callbackToken) {
            $headerToken = $request->header('x-callback-token');
            if (!$headerToken || $headerToken !== $callbackToken) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }
        }

        // Xendit mengirim invoice ID di field 'id'
        $invoiceId = $request->input('id');
        $status = strtoupper($request->input('status'));
        $externalId = $request->input('external_id'); // kode_transaksi kita

        if (!$invoiceId || !$status) {
            return response()->json(['success' => false, 'message' => 'Payload tidak valid'], 400);
        }

        // Cari pembayaran berdasarkan external_id (invoice ID dari Xendit)
        $pembayaran = Pembayaran::where('external_id', $invoiceId)->first();

        // Fallback: cari berdasarkan kode_transaksi jika tidak ketemu
        if (!$pembayaran && $externalId) {
            $pembayaran = Pembayaran::where('kode_transaksi', $externalId)->first();
        }

        if (!$pembayaran) {
            return response()->json(['success' => false, 'message' => 'Pembayaran tidak ditemukan'], 404);
        }

        // Handle status PAID
        if ($status === 'PAID') {
            if ($pembayaran->status !== 'berhasil') {
                $paidAt = $request->input('paid_at');
                $pembayaran->update([
                    'status' => 'berhasil',
                    'waktu_bayar' => $paidAt ? \Carbon\Carbon::parse($paidAt) : now(),
                ]);

                if ($pembayaran->tiket && $pembayaran->tiket->status !== 'dibayar') {
                    $pembayaran->tiket->update(['status' => 'dibayar']);
                }
            }
        }
        // Handle status EXPIRED
        elseif ($status === 'EXPIRED') {
            if ($pembayaran->status !== 'berhasil') {
                $pembayaran->update(['status' => 'gagal']);

                // Optional: batal tiket jika expired
                if ($pembayaran->tiket && $pembayaran->tiket->status === 'dipesan') {
                    $pembayaran->tiket->update(['status' => 'batal']);
                }
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

    /**
     * Cek status pembayaran dari Xendit (jika webhook gagal)
     * GET /api/pembayaran/{id}/check-status
     */
    public function checkStatus($id)
    {
        $pembayaran = Pembayaran::with('tiket')->findOrFail($id);

        // Hanya untuk pembayaran xendit
        if ($pembayaran->metode !== 'xendit') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pembayaran Xendit yang bisa dicek statusnya',
            ], 422);
        }

        if (!$pembayaran->external_id) {
            return response()->json([
                'success' => false,
                'message' => 'External ID tidak ditemukan',
            ], 404);
        }

        // Query status invoice ke Xendit
        $xenditResponse = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode(config('services.xendit.api_key') . ':'),
        ])->get(config('services.xendit.base_url') . '/v2/invoices/' . $pembayaran->external_id);

        if ($xenditResponse->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil status dari Xendit',
                'error' => $xenditResponse->json(),
            ], 500);
        }

        $invoiceData = $xenditResponse->json();
        $xenditStatus = strtoupper($invoiceData['status'] ?? '');

        // Sync status jika berbeda
        if ($xenditStatus === 'PAID' && $pembayaran->status !== 'berhasil') {
            $paidAt = $invoiceData['paid_at'] ?? null;
            $pembayaran->update([
                'status' => 'berhasil',
                'waktu_bayar' => $paidAt ? \Carbon\Carbon::parse($paidAt) : now(),
            ]);

            if ($pembayaran->tiket && $pembayaran->tiket->status !== 'dibayar') {
                $pembayaran->tiket->update(['status' => 'dibayar']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil disinkronkan: PAID',
                'data' => [
                    'id' => $pembayaran->id,
                    'kode_transaksi' => $pembayaran->kode_transaksi,
                    'status' => $pembayaran->status,
                    'xendit_status' => $xenditStatus,
                    'waktu_bayar' => $pembayaran->waktu_bayar,
                ],
            ]);
        } elseif ($xenditStatus === 'EXPIRED' && $pembayaran->status !== 'gagal') {
            $pembayaran->update(['status' => 'gagal']);

            if ($pembayaran->tiket && $pembayaran->tiket->status === 'dipesan') {
                $pembayaran->tiket->update(['status' => 'batal']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil disinkronkan: EXPIRED',
                'data' => [
                    'id' => $pembayaran->id,
                    'kode_transaksi' => $pembayaran->kode_transaksi,
                    'status' => $pembayaran->status,
                    'xendit_status' => $xenditStatus,
                ],
            ]);
        }

        // Status sudah sync
        return response()->json([
            'success' => true,
            'message' => 'Status sudah sinkron',
            'data' => [
                'id' => $pembayaran->id,
                'kode_transaksi' => $pembayaran->kode_transaksi,
                'status' => $pembayaran->status,
                'xendit_status' => $xenditStatus,
                'waktu_bayar' => $pembayaran->waktu_bayar,
            ],
        ]);
    }
}
