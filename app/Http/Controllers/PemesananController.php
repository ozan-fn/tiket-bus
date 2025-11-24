<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Tiket;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PemesananController extends Controller
{
    public function index(Request $request): View
    {
        $jadwals = Jadwal::with('bus', 'sopir.user', 'rute.asalTerminal', 'rute.tujuanTerminal')
            ->where('status', 'tersedia')
            ->when($request->asal, fn($q) => $q->whereHas('rute.asalTerminal', fn($q2) => $q2->where('nama_terminal', 'like', '%' . $request->asal . '%')))
            ->when($request->tujuan, fn($q) => $q->whereHas('rute.tujuanTerminal', fn($q2) => $q2->where('nama_terminal', 'like', '%' . $request->tujuan . '%')))
            ->when($request->tanggal, fn($q) => $q->whereDate('tanggal_berangkat', $request->tanggal))
            ->paginate(10);

        return view('pemesanan.index', compact('jadwals'));
    }

    public function create(Jadwal $jadwal): View
    {
        $jadwal->load('bus', 'sopir.user', 'rute.asalTerminal', 'rute.tujuanTerminal');
        $kursiTerpakai = Tiket::where('jadwal_id', $jadwal->id)->pluck('kursi')->toArray();
        return view('pemesanan.create', compact('jadwal', 'kursiTerpakai'));
    }

    public function store(Request $request, Jadwal $jadwal): RedirectResponse
    {
        $request->validate([
            'nama_penumpang' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'nomor_telepon' => 'required|string|max:20',
            'email' => 'required|email',
            'kursi' => 'required|integer|min:1|max:' . $jadwal->bus->kapasitas,
        ]);

        // Cek kursi sudah dipesan atau belum
        $kursiTerpakai = Tiket::where('jadwal_id', $jadwal->id)->pluck('kursi')->toArray();
        if (in_array($request->kursi, $kursiTerpakai)) {
            return back()->withErrors(['kursi' => 'Kursi sudah dipesan']);
        }

        Tiket::create([
            'jadwal_id' => $jadwal->id,
            'user_id' => auth()->id(),
            'nama_penumpang' => $request->nama_penumpang,
            'jenis_kelamin' => $request->jenis_kelamin,
            'nomor_telepon' => $request->nomor_telepon,
            'email' => $request->email,
            'kursi' => $request->kursi,
            'status' => 'dipesan',
        ]);

        return redirect()->route('pemesanan.index')->with('success', 'Tiket berhasil dipesan');
    }

    public function show(Tiket $tiket): View
    {
        // Otorisasi sederhana: hanya pemilik atau admin/super_admin
        $user = auth()->user();
        if ($tiket->user_id !== ($user?->id) && !$user?->hasRole('admin') && !$user?->hasRole('super_admin')) {
            abort(403, 'Anda tidak berhak melihat tiket ini');
        }

        // Eager load fallback: jika kolom jadwal_id kosong gunakan jalur jadwalKelasBus
        $tiket->load([
            'jadwal.bus',
            'jadwal.sopir.user',
            'jadwal.rute.asalTerminal',
            'jadwal.rute.tujuanTerminal',
            'jadwalKelasBus.jadwal.bus',
            'jadwalKelasBus.jadwal.sopir.user',
            'jadwalKelasBus.jadwal.rute.asalTerminal',
            'jadwalKelasBus.jadwal.rute.tujuanTerminal',
            'kursi'
        ]);
        return view('pemesanan.show', compact('tiket'));
    }

    public function history(Request $request): View
    {
        $tikets = Tiket::with([
            'jadwalKelasBus.jadwal.bus',
            'jadwalKelasBus.jadwal.sopir.user',
            'jadwalKelasBus.jadwal.rute.asalTerminal',
            'jadwalKelasBus.jadwal.rute.tujuanTerminal',
            'jadwalKelasBus.kelasBus',
            'kursi',
            'user'
        ])
            ->whereHas('jadwalKelasBus.jadwal') // Pastikan ada jadwal
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->tanggal, fn($q) => $q->whereDate('waktu_pesan', $request->tanggal))
            ->when($request->nama, fn($q) => $q->where('nama_penumpang', 'like', '%' . $request->nama . '%'))
            ->when($request->kode_tiket, fn($q) => $q->where('kode_tiket', 'like', '%' . $request->kode_tiket . '%'))
            ->orderBy('waktu_pesan', 'desc')
            ->paginate(20);

        return view('admin.history-pemesanan', compact('tikets'));
    }
}
