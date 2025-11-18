<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Rute;
use App\Models\Bus;

class JadwalBusController extends Controller
{
    // Menampilkan jadwal bus dengan filter tanggal, rute, status
// Membuat jadwal bus (khusus admin/operator)
    public function store(Request $request)
    {
        $request->validate([
            'bus_id' => 'required|exists:bus,id',
            'sopir_id' => 'required|exists:sopir,id',
            'rute_id' => 'required|exists:rute,id',
            'tanggal_berangkat' => 'required|date',
            'jam_berangkat' => 'required|date_format:H:i',
            'status' => 'required|string',
            'kelas_bus' => 'required|array',
            'kelas_bus.*.kelas_bus_id' => 'required|exists:kelas_bus,id',
            'kelas_bus.*.harga' => 'required|numeric|min:0',
        ]);

        $jadwal = Jadwal::create([
            'bus_id' => $request->bus_id,
            'sopir_id' => $request->sopir_id,
            'rute_id' => $request->rute_id,
            'tanggal_berangkat' => $request->tanggal_berangkat,
            'jam_berangkat' => $request->jam_berangkat,
            'status' => $request->status,
        ]);

        // Simpan harga kelas bus untuk jadwal ini
        foreach ($request->kelas_bus as $kelas) {
            $jadwal->jadwalKelasBus()->create([
                'kelas_bus_id' => $kelas['kelas_bus_id'],
                'harga' => $kelas['harga'],
            ]);
        }

        return response()->json(['message' => 'Jadwal bus berhasil dibuat', 'jadwal' => $jadwal], 201);
    }
    public function index(Request $request)
    {
        $query = Jadwal::with([
            'bus.fasilitas',
            'sopir',
            'rute.terminal',
            'jadwalKelasBus.kelasBus'
        ]);

        if ($request->has('tanggal')) {
            $query->whereDate('tanggal_berangkat', $request->tanggal);
        }
        if ($request->has('rute_id')) {
            $query->where('rute_id', $request->rute_id);
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $jadwal = $query->get();
        $result = $jadwal->map(function ($item) {
            return [
                'id' => $item->id,
                'tanggal_berangkat' => $item->tanggal_berangkat,
                'jam_berangkat' => $item->jam_berangkat,
                'status' => $item->status,
                'bus' => [
                    'id' => $item->bus->id ?? null,
                    'nama' => $item->bus->nama ?? null,
                    'plat_nomor' => $item->bus->plat_nomor ?? null,
                    'kapasitas' => $item->bus->kapasitas ?? null,
                    'tipe' => $item->bus->tipe ?? null,
                    'status' => $item->bus->status ?? null,
                    'fasilitas' => $item->bus->fasilitas ?? []
                ],
                'sopir' => $item->sopir,
                'rute' => [
                    'id' => $item->rute->id ?? null,
                    'asal_terminal' => $item->rute->asal_terminal_id ?? null,
                    'tujuan_terminal' => $item->rute->tujuan_terminal_id ?? null,
                    'terminal' => $item->rute->terminal ?? []
                ],
                'kelas_bus' => $item->jadwalKelasBus->map(function ($kelas) {
                    return [
                        'id' => $kelas->kelasBus->id ?? null,
                        'nama_kelas' => $kelas->kelasBus->nama_kelas ?? null,
                        'jumlah_kursi' => $kelas->kelasBus->jumlah_kursi ?? null,
                        'harga' => $kelas->harga ?? null
                    ];
                }),
            ];
        });
        return response()->json($result);
    }

    // Detail jadwal bus
    public function show($id)
    {
        $jadwal = Jadwal::with([
            'bus.fasilitas',
            'sopir',
            'rute.terminal',
            'jadwalKelasBus.kelasBus'
        ])->findOrFail($id);
        $result = [
            'id' => $jadwal->id,
            'tanggal_berangkat' => $jadwal->tanggal_berangkat,
            'jam_berangkat' => $jadwal->jam_berangkat,
            'status' => $jadwal->status,
            'bus' => [
                'id' => $jadwal->bus->id ?? null,
                'nama' => $jadwal->bus->nama ?? null,
                'plat_nomor' => $jadwal->bus->plat_nomor ?? null,
                'kapasitas' => $jadwal->bus->kapasitas ?? null,
                'tipe' => $jadwal->bus->tipe ?? null,
                'status' => $jadwal->bus->status ?? null,
                'fasilitas' => $jadwal->bus->fasilitas ?? []
            ],
            'sopir' => $jadwal->sopir,
            'rute' => [
                'id' => $jadwal->rute->id ?? null,
                'asal_terminal' => $jadwal->rute->asal_terminal_id ?? null,
                'tujuan_terminal' => $jadwal->rute->tujuan_terminal_id ?? null,
                'terminal' => $jadwal->rute->terminal ?? []
            ],
            'kelas_bus' => $jadwal->jadwalKelasBus->map(function ($kelas) {
                return [
                    'id' => $kelas->kelasBus->id ?? null,
                    'nama_kelas' => $kelas->kelasBus->nama_kelas ?? null,
                    'jumlah_kursi' => $kelas->kelasBus->jumlah_kursi ?? null,
                    'harga' => $kelas->harga ?? null
                ];
            }),
        ];
        return response()->json($result);
    }
}