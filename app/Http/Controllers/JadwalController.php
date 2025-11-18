<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Bus;
use App\Models\Sopir;
use App\Models\Rute;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class JadwalController extends Controller
{
    public function index(): View
    {
        $jadwals = Jadwal::with('bus', 'sopir.user', 'rute.asalTerminal', 'rute.tujuanTerminal')->paginate(10);
        return view('jadwal.index', compact('jadwals'));
    }

    public function create(): View
    {
        $buses = Bus::all();
        $sopirs = Sopir::with('user')->where('status', 'aktif')->get();
        $rutes = Rute::with('asalTerminal', 'tujuanTerminal')->get();
        return view('jadwal.create', compact('buses', 'sopirs', 'rutes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'bus_id' => 'required|exists:bus,id',
            'sopir_id' => 'required|exists:sopir,id',
            'rute_id' => 'required|exists:rute,id',
            'tanggal_berangkat' => 'required|date|after:today',
            'jam_berangkat' => 'required|date_format:H:i',
            'status' => 'required|in:aktif,tidak_aktif',
        ]);

        Jadwal::create($request->all());

        return redirect()->route('admin/jadwal.index')->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function show(Jadwal $jadwal): View
    {
        $jadwal->load('bus', 'sopir.user', 'rute.asalTerminal', 'rute.tujuanTerminal');
        return view('jadwal.show', compact('jadwal'));
    }

    public function edit(Jadwal $jadwal): View
    {
        $buses = Bus::all();
        $sopirs = Sopir::with('user')->where('status', 'aktif')->get();
        $rutes = Rute::with('asalTerminal', 'tujuanTerminal')->get();
        return view('jadwal.edit', compact('jadwal', 'buses', 'sopirs', 'rutes'));
    }

    public function update(Request $request, Jadwal $jadwal): RedirectResponse
    {
        $request->validate([
            'bus_id' => 'required|exists:bus,id',
            'sopir_id' => 'required|exists:sopir,id',
            'rute_id' => 'required|exists:rute,id',
            'tanggal_berangkat' => 'required|date',
            'jam_berangkat' => 'required|date_format:H:i',
            'status' => 'required|in:aktif,tidak_aktif',
        ]);

        $jadwal->update($request->all());

        return redirect()->route('admin/jadwal.index')->with('success', 'Jadwal berhasil diperbarui');
    }

    public function destroy(Jadwal $jadwal): RedirectResponse
    {
        $jadwal->delete();

        return redirect()->route('admin/jadwal.index')->with('success', 'Jadwal berhasil dihapus');
    }
}