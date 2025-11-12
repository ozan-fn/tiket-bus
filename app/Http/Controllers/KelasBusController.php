<?php

namespace App\Http\Controllers;

use App\Models\KelasBus;
use Illuminate\Http\Request;

class KelasBusController extends Controller
{
    // List semua kelas bus
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        return response()->json(KelasBus::with('bus')->paginate($perPage));
    }

    // Tambah kelas bus
    public function store(Request $request)
    {
        $request->validate([
            'bus_id' => 'required|exists:bus,id',
            'nama_kelas' => 'required|string',
            'posisi' => 'nullable|string',
            'jumlah_kursi' => 'nullable|integer',
        ]);
        $kelasBus = KelasBus::create($request->all());
        return response()->json($kelasBus, 201);
    }

    // Detail kelas bus
    public function show($id)
    {
        $kelasBus = KelasBus::with('bus')->findOrFail($id);
        return response()->json($kelasBus);
    }

    // Update kelas bus
    public function update(Request $request, $id)
    {
        $kelasBus = KelasBus::findOrFail($id);
        $kelasBus->update($request->all());
        return response()->json($kelasBus);
    }

    // Hapus kelas bus
    public function destroy($id)
    {
        $kelasBus = KelasBus::findOrFail($id);
        $kelasBus->delete();
        return response()->json(['message' => 'Kelas bus dihapus']);
    }
}
