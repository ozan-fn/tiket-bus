<?php

namespace App\Http\Controllers;

use App\Models\Tiket;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TiketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request): View
    {
        $query = Tiket::where('user_id', auth()->id())
            ->with([
                'jadwalKelasBus.jadwal.rute.asalTerminal',
                'jadwalKelasBus.jadwal.rute.tujuanTerminal',
                'jadwalKelasBus.jadwal.bus',
                'jadwalKelasBus.jadwal.sopir.user',
                'jadwalKelasBus.kelasBus',
                'kursi',
                'pembayaran'
            ]);

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $tikets = $query->orderBy('waktu_pesan', 'desc')
            ->paginate(10);

        return view('tiket.index', compact('tikets'));
    }

    public function show(Tiket $tiket): View
    {
        // Otorisasi: hanya pemilik tiket yang bisa lihat
        if ($tiket->user_id !== auth()->id()) {
            abort(403, "Anda tidak berhak melihat tiket ini");
        }

        // Load relasi
        $tiket->load([
            'jadwalKelasBus.jadwal.bus',
            'jadwalKelasBus.jadwal.sopir.user',
            'jadwalKelasBus.jadwal.rute.asalTerminal',
            'jadwalKelasBus.jadwal.rute.tujuanTerminal',
            'jadwalKelasBus.kelasBus',
            'kursi',
            'pembayaran'
        ]);

        return view('tiket.show', compact('tiket'));
    }
}
