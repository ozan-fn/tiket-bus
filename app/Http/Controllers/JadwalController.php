<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Bus;
use App\Models\Sopir;
use App\Models\Rute;
use Carbon\Carbon;

class JadwalController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $jadwals = Jadwal::with('bus', 'sopir.user', 'rute.asalTerminal', 'rute.tujuanTerminal')->paginate(10);
        return view('jadwal.index', compact('jadwals'));
    }

    public function create(): \Illuminate\View\View
    {
        $buses = Bus::all();
        $sopirs = Sopir::with('user')->where('status', 'aktif')->get();
        $rutes = Rute::with('asalTerminal', 'tujuanTerminal')->get();
        return view('jadwal.create', compact('buses', 'sopirs', 'rutes'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'bus_id' => 'required|exists:bus,id',
            'sopir_id' => 'required|exists:sopir,id',
            'rute_id' => 'required|exists:rute,id',
            'tanggal_berangkat' => 'required|date|after:today',
            'jam_berangkat' => 'required|date_format:H:i',
            'status' => 'required|in:aktif,tidak_aktif',
            'is_recurring' => 'nullable|boolean',
            'recurring_type' => 'nullable|in:daily,weekly',
            'recurring_count' => 'nullable|integer|min:1|max:90',
        ]);

        if ($request->is_recurring) {
            $jadwals = [];
            $tanggal = Carbon::parse($request->tanggal_berangkat);
            $interval = $request->recurring_type === 'weekly' ? 7 : 1;

            for ($i = 0; $i < $request->recurring_count; $i++) {
                $jadwals[] = [
                    'bus_id' => $request->bus_id,
                    'sopir_id' => $request->sopir_id,
                    'rute_id' => $request->rute_id,
                    'tanggal_berangkat' => $tanggal->toDateString(),
                    'jam_berangkat' => $request->jam_berangkat,
                    'status' => $request->status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $tanggal->addDays($interval);
            }

            Jadwal::insert($jadwals);
            $message = count($jadwals) . ' jadwal berhasil ditambahkan';
        } else {
            Jadwal::create($request->only(['bus_id', 'sopir_id', 'rute_id', 'tanggal_berangkat', 'jam_berangkat', 'status']));
            $message = 'Jadwal berhasil ditambahkan';
        }

        return redirect()->route('admin/jadwal.index')->with('success', $message);
    }

    public function show(Jadwal $jadwal): \Illuminate\View\View
    {
        $jadwal->load('bus', 'sopir.user', 'rute.asalTerminal', 'rute.tujuanTerminal');
        return view('jadwal.show', compact('jadwal'));
    }

    public function edit(Jadwal $jadwal): \Illuminate\View\View
    {
        $buses = Bus::all();
        $sopirs = Sopir::with('user')->where('status', 'aktif')->get();
        $rutes = Rute::with('asalTerminal', 'tujuanTerminal')->get();
        return view('jadwal.edit', compact('jadwal', 'buses', 'sopirs', 'rutes'));
    }

    public function update(Request $request, Jadwal $jadwal): \Illuminate\Http\RedirectResponse
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

    public function destroy(Jadwal $jadwal): \Illuminate\Http\RedirectResponse
    {
        $jadwal->delete();

        return redirect()->route('admin/jadwal.index')->with('success', 'Jadwal berhasil dihapus');
    }
}