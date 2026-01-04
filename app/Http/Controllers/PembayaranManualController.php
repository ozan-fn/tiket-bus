<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Tiket;
use Illuminate\Support\Facades\DB;

class PembayaranManualController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pembayaran::with(["user", "tiket.jadwalKelasBus.jadwal.bus", "tiket.jadwalKelasBus.jadwal.rute.asalTerminal", "tiket.jadwalKelasBus.jadwal.rute.tujuanTerminal"]);

        // Search by kode_transaksi or user name
        if ($search = $request->input("search")) {
            $query->where(function ($q) use ($search) {
                $q->where("kode_transaksi", "like", "%{$search}%")->orWhereHas("user", function ($userQuery) use ($search) {
                    $userQuery->where("name", "like", "%{$search}%");
                });
            });
        }

        // Filter by status
        if ($status = $request->input("status")) {
            $query->where("status", $status);
        }

        // Filter by metode
        if ($metode = $request->input("metode")) {
            $query->where("metode", $metode);
        }

        $pembayaran = $query->orderBy("created_at", "desc")->paginate(10);

        return view("admin.pembayaran-manual", compact("pembayaran"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil tiket yang statusnya masih 'dipesan'
        // Kita juga perlu mengizinkan tiket yang sudah punya pembayaran TAPI status pembayarannya gagal/kadaluarsa
        $tikets = Tiket::with(["user", "jadwalKelasBus.jadwal.bus", "jadwalKelasBus.jadwal.rute.asalTerminal", "jadwalKelasBus.jadwal.rute.tujuanTerminal"])
            ->where('status', 'dipesan') // Hanya tiket yang belum lunas
            ->whereDoesntHave('pembayaran', function ($q) {
                $q->where('status', 'dibayar'); // Pastikan tidak ambil tiket yang SUDAH LUNAS
            })
            ->get();

        return view("admin.pembayaran-manual.create", compact("tikets"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "tiket_id" => "required|exists:tiket,id",
            "metode" => "required|in:tunai,transfer,xendit",
            "nominal" => "required|numeric|min:0",
            "status" => "required|in:dipesan,dibayar,batal,selesai",
            "waktu_bayar" => "nullable|date",
        ]);

        try {
            DB::beginTransaction();

            $tiket = Tiket::findOrFail($request->tiket_id);

            // Auto-set waktu_bayar if status is 'dibayar' and waktu_bayar is not provided
            $waktuBayar = $request->waktu_bayar;
            if ($request->status === "dibayar" && !$waktuBayar) {
                $waktuBayar = now();
            }

            // PENTING: Karena relasi hasOne, kita pakai updateOrCreate.
            // Jika tiket sudah punya record pembayaran (misal bekas Xendit pending), kita timpa saja.
            // Jika belum ada, kita buat baru.
            $pembayaran = Pembayaran::updateOrCreate(
                ['tiket_id' => $tiket->id], // Kunci pencarian (Foreign Key)
                [
                    "user_id" => $tiket->user_id,
                    "metode" => $request->metode,
                    "nominal" => $request->nominal,
                    "status" => $request->status,
                    "waktu_bayar" => $waktuBayar,
                    // Jika buat baru generate kode, jika update biarkan kode lama (atau mau di-force generate baru juga boleh)
                    "kode_transaksi" => function () use ($tiket) {
                        // Cek apakah pembayaran lama ada kodenya? Kalau tidak, generate.
                        return $tiket->pembayaran->kode_transaksi ?? "PM-" . strtoupper(uniqid());
                    },
                    // Reset field external (jika sebelumnya dari xendit)
                    "external_id" => null,
                    "bukti_pembayaran" => null
                ]
            );

            // Jika kode_transaksi terhapus karena updateOrCreate logic di atas, pastikan terisi
            if (!$pembayaran->kode_transaksi) {
                $pembayaran->update(["kode_transaksi" => "PM-" . strtoupper(uniqid())]);
            }

            // Sinkronisasi status Tiket
            $this->syncTiketStatus($tiket, $request->status);

            DB::commit();
            return redirect()->route("admin/pembayaran-manual.index")->with("success", "Pembayaran manual berhasil diproses");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pembayaran $pembayaran)
    {
        $pembayaran->load(["user", "tiket.jadwalKelasBus.jadwal.bus", "tiket.jadwalKelasBus.jadwal.rute.asalTerminal", "tiket.jadwalKelasBus.jadwal.rute.tujuanTerminal"]);

        return view("admin.pembayaran-manual.show", compact("pembayaran"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pembayaran $pembayaran)
    {
        return view("admin.pembayaran-manual.edit", compact("pembayaran"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pembayaran $pembayaran)
    {
        $request->validate([
            "metode" => "required|in:tunai,transfer,xendit",
            "nominal" => "required|numeric|min:0",
            "status" => "required|in:dipesan,dibayar,batal,selesai",
            "waktu_bayar" => "nullable|date",
        ]);

        try {
            DB::beginTransaction();

            // Auto-set waktu_bayar if status is 'dibayar' and waktu_bayar is not provided
            $updateData = $request->only(["metode", "nominal", "status", "waktu_bayar"]);
            if ($request->status === "dibayar" && !$request->waktu_bayar) {
                $updateData["waktu_bayar"] = now();
            }

            // Update data pembayaran
            $pembayaran->update($updateData);

            // Sinkronisasi status Tiket
            // Kita ambil tiket terbaru dari relasi
            if ($pembayaran->tiket) {
                $this->syncTiketStatus($pembayaran->tiket, $request->status);
            }

            DB::commit();
            return redirect()->route("admin/pembayaran-manual.index")->with("success", "Pembayaran manual berhasil diperbarui");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal update: ' . $e->getMessage()]);
        }
    }

    /**
     * Confirm/Approve a pending payment (change status from dipesan to dibayar).
     */
    public function confirm(Pembayaran $pembayaran)
    {
        // Validasi: hanya pembayaran 'dipesan' yang bisa dikonfirmasi
        if ($pembayaran->status !== 'dipesan') {
            return redirect()->back()->withErrors(['error' => 'Hanya pembayaran yang pending (dipesan) yang bisa dikonfirmasi.']);
        }

        try {
            DB::beginTransaction();

            // Update status pembayaran menjadi 'dibayar' dan set waktu_bayar
            $pembayaran->update([
                'status' => 'dibayar',
                'waktu_bayar' => now(),
            ]);

            // Sinkronisasi status tiket menjadi 'dibayar'
            if ($pembayaran->tiket) {
                $this->syncTiketStatus($pembayaran->tiket, 'dibayar');
            }

            DB::commit();
            return redirect()->route('admin/pembayaran-manual.index')->with('success', 'Pembayaran berhasil dikonfirmasi. Status tiket telah diperbarui menjadi dibayar.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal konfirmasi: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pembayaran $pembayaran)
    {
        // Jangan bisa delete pembayaran yang sudah dibayar (Hard validation)
        if ($pembayaran->status === "dibayar") {
            return redirect()->route("admin/pembayaran-manual.index")->withErrors(["error" => "Tidak dapat menghapus pembayaran yang sudah dibayar/lunas."]);
        }

        try {
            DB::beginTransaction();

            $tiket = $pembayaran->tiket;

            // Hapus pembayaran
            $pembayaran->delete();

            // Kembalikan status tiket ke 'dipesan' (pending) karena pembayarannya hilang
            if ($tiket) {
                $tiket->update(["status" => "dipesan"]);
            }

            DB::commit();
            return redirect()->route("admin/pembayaran-manual.index")->with("success", "Pembayaran manual berhasil dihapus & status tiket dikembalikan.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal hapus: ' . $e->getMessage()]);
        }
    }

    /**
     * Helper: Sinkronisasi Status Tiket berdasarkan Status Pembayaran
     */
    private function syncTiketStatus(Tiket $tiket, string $statusPembayaran)
    {
        /*
         * Logika Mapping:
         * Pembayaran 'dibayar' -> Tiket 'dibayar'
         * Pembayaran 'batal'   -> Tiket 'batal'
         * Pembayaran 'dipesan' -> Tiket 'dipesan'
         * Pembayaran 'selesai' -> Tiket 'dibayar' (Biasanya tiket 'selesai' itu kalau sudah scan QR, bukan dari pembayaran)
         */

        $newStatus = 'dipesan'; // Default fallback

        if ($statusPembayaran === 'dibayar') {
            $newStatus = 'dibayar';
        } elseif ($statusPembayaran === 'batal') {
            $newStatus = 'batal';
        } elseif ($statusPembayaran === 'selesai') {
            // Asumsi: jika pembayaran dianggap selesai, tiket statusnya dibayar (siap pakai)
            $newStatus = 'dibayar';
        }

        // Update hanya jika status berbeda
        if ($tiket->status !== $newStatus) {
            $tiket->status = $newStatus;
            $tiket->save();
        }
    }
}