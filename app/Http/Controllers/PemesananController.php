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
            ->where('status', 'aktif')
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
        $this->authorize('view', $tiket); // Pastikan user yang pesan
        $tiket->load('jadwal.bus', 'jadwal.sopir.user', 'jadwal.rute.asalTerminal', 'jadwal.rute.tujuanTerminal');
        return view('pemesanan.show', compact('tiket'));
    }
}
