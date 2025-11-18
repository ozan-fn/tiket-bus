<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use Illuminate\Http\Request;

class FasilitasController extends Controller
{
    // Menampilkan semua fasilitas
    public function index(Request $request)
    {
        $query = Fasilitas::query();

        // Conditional eager loading based on 'include' query parameter
        if ($request->has('include')) {
            $includes = explode(',', $request->include);
            // Filter to only allowed relations for security
            $allowed = ['buses'];
            $validIncludes = array_intersect($includes, $allowed);
            if (!empty($validIncludes)) {
                $query->with($validIncludes);
            }
        }

        $perPage = $request->get('per_page', 10);
        $fasilitas = $query->paginate($perPage);
        return response()->json($fasilitas);
    }

    // Menambah fasilitas baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);
        $fasilitas = Fasilitas::create([
            'nama' => $request->nama,
        ]);
        return response()->json($fasilitas, 201);
    }

    // Menampilkan detail fasilitas
    public function show(Request $request, $id)
    {
        $query = Fasilitas::query();

        // Conditional eager loading based on 'include' query parameter
        if ($request->has('include')) {
            $includes = explode(',', $request->include);
            // Filter to only allowed relations for security
            $allowed = ['buses'];
            $validIncludes = array_intersect($includes, $allowed);
            if (!empty($validIncludes)) {
                $query->with($validIncludes);
            }
        }

        $fasilitas = $query->findOrFail($id);
        return response()->json($fasilitas);
    }

    // Update fasilitas
    public function update(Request $request, $id)
    {
        $fasilitas = Fasilitas::findOrFail($id);
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);
        $fasilitas->update([
            'nama' => $request->nama,
        ]);
        return response()->json($fasilitas);
    }

    // Hapus fasilitas
    public function destroy($id)
    {
        $fasilitas = Fasilitas::findOrFail($id);
        $fasilitas->delete();
        return response()->json(['message' => 'Fasilitas dihapus']);
    }
}