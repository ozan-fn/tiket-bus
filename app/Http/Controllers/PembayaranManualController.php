<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Tiket;

class PembayaranManualController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Pembayaran::with(["user", "tiket.jadwalKelasBus.jadwal.bus", "tiket.jadwalKelasBus.jadwal.rute.asalTerminal", "tiket.jadwalKelasBus.jadwal.rute.tujuanTerminal"]);

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
        $tikets = Tiket::with(["user", "jadwalKelasBus.jadwal.bus", "jadwalKelasBus.jadwal.rute.asalTerminal", "jadwalKelasBus.jadwal.rute.tujuanTerminal"])
            ->whereDoesntHave("pembayaran")
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

        $tiket = Tiket::findOrFail($request->tiket_id);

        // Auto-set waktu_bayar if status is 'dibayar' and waktu_bayar is not provided
        $waktuBayar = $request->waktu_bayar;
        if ($request->status === "dibayar" && !$waktuBayar) {
            $waktuBayar = now();
        }

        $pembayaran = Pembayaran::create([
            "user_id" => $tiket->user_id,
            "tiket_id" => $tiket->id,
            "metode" => $request->metode,
            "nominal" => $request->nominal,
            "status" => $request->status,
            "waktu_bayar" => $waktuBayar,
            "kode_transaksi" => "PM-" . strtoupper(uniqid()),
        ]);

        return redirect()->route("admin/pembayaran-manual.index")->with("success", "Pembayaran manual berhasil ditambahkan");
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

        // Auto-set waktu_bayar if status is 'dibayar' and waktu_bayar is not provided
        $updateData = $request->only(["metode", "nominal", "status", "waktu_bayar"]);
        if ($request->status === "dibayar" && !$request->waktu_bayar) {
            $updateData["waktu_bayar"] = now();
        }

        $pembayaran->update($updateData);

        // Update status tiket sesuai dengan status pembayaran
        if ($request->status === "dibayar" && $pembayaran->tiket->status !== "dibayar") {
            $pembayaran->tiket->update(["status" => "dibayar"]);
        } elseif ($request->status === "batal" && $pembayaran->tiket->status !== "batal") {
            $pembayaran->tiket->update(["status" => "batal"]);
        }

        return redirect()->route("admin/pembayaran-manual.index")->with("success", "Pembayaran manual berhasil diperbarui");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pembayaran $pembayaran)
    {
        $pembayaran->delete();

        return redirect()->route("admin/pembayaran-manual.index")->with("success", "Pembayaran manual berhasil dihapus");
    }
}
