<?php

namespace App\Http\Controllers;

use App\Models\JadwalKelasBus;
use Illuminate\Http\Request;

class JadwalKelasBusController extends Controller
{
    // List semua kelas di jadwal
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        return response()->json(JadwalKelasBus::with(['jadwal', 'kelasBus'])->paginate($perPage));
    }

    // Tambah kelas ke jadwal
    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwal,id',
            'kelas_bus_id' => 'required|exists:kelas_bus,id',
            'harga' => 'required|integer',
        ]);
        $jadwalKelasBus = JadwalKelasBus::create($request->all());
        return response()->json($jadwalKelasBus, 201);
    }

    // Detail kelas di jadwal
    public function show($id)
    {
        $jadwalKelasBus = JadwalKelasBus::with(['jadwal', 'kelasBus'])->findOrFail($id);
        return response()->json($jadwalKelasBus);
    }

    // Update harga kelas di jadwal
    public function update(Request $request, $id)
    {
        $jadwalKelasBus = JadwalKelasBus::findOrFail($id);
        $jadwalKelasBus->update($request->all());
        return response()->json($jadwalKelasBus);
    }

    // Hapus kelas di jadwal
    public function destroy($id)
    {
        $jadwalKelasBus = JadwalKelasBus::findOrFail($id);
        $jadwalKelasBus->delete();
        return response()->json(['message' => 'Kelas di jadwal dihapus']);
    }
}
