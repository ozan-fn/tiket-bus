<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BusController extends Controller
{
    // List all buses
    public function index(Request $request)
    {
        $query = Bus::query();

        // Conditional eager loading based on 'include' query parameter
        if ($request->has('include')) {
            $includes = explode(',', $request->include);
            // Filter to only allowed relations for security
            $allowed = ['fasilitas', 'jadwals'];
            $validIncludes = array_intersect($includes, $allowed);
            if (!empty($validIncludes)) {
                $query->with($validIncludes);
            }
        }

        $perPage = $request->get('per_page', 10);
        $buses = $query->paginate($perPage);
        return response()->json($buses);
    }

    // Create a new bus
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'plat_nomor' => 'required|string|max:50|unique:bus,plat_nomor',
            'kapasitas' => 'required|integer|min:1',
            'status' => 'required|in:aktif,nonaktif',
            'keterangan' => 'nullable|string',
            'fasilitas_ids' => 'nullable|array',
            'fasilitas_ids.*' => 'exists:fasilitas,id',
            'jadwals' => 'nullable|array',
            'jadwals.*.sopir_id' => 'required_with:jadwals|exists:sopir,id',
            'jadwals.*.rute_id' => 'required_with:jadwals|exists:rute,id',
            'jadwals.*.tanggal_berangkat' => 'required_with:jadwals|date',
            'jadwals.*.jam_berangkat' => 'required_with:jadwals|date_format:H:i',
            'jadwals.*.status' => 'required_with:jadwals|in:aktif,nonaktif',
        ]);

        $bus = DB::transaction(function () use ($request) {
            $bus = Bus::create($request->only(['nama', 'plat_nomor', 'kapasitas', 'status', 'keterangan']));

            if ($request->has('fasilitas_ids')) {
                $bus->fasilitas()->attach($request->fasilitas_ids);
            }

            if ($request->has('jadwals')) {
                foreach ($request->jadwals as $jadwalData) {
                    $jadwalData['bus_id'] = $bus->id;
                    Jadwal::create($jadwalData);
                }
            }

            return $bus;
        });

        return response()->json($bus, 201);
    }

    // Show a specific bus
    public function show(Request $request, $id)
    {
        $query = Bus::query();

        // Conditional eager loading based on 'include' query parameter
        if ($request->has('include')) {
            $includes = explode(',', $request->include);
            // Filter to only allowed relations for security
            $allowed = ['fasilitas', 'jadwals'];
            $validIncludes = array_intersect($includes, $allowed);
            if (!empty($validIncludes)) {
                $query->with($validIncludes);
            }
        }

        $bus = $query->findOrFail($id);
        return response()->json($bus);
    }

    // Update a specific bus
    public function update(Request $request, $id)
    {
        $bus = Bus::findOrFail($id);

        $request->validate([
            'nama' => 'sometimes|required|string|max:255',
            'plat_nomor' => "sometimes|required|string|max:50|unique:bus,plat_nomor,$id", // Ignore current bus
            'kapasitas' => 'sometimes|required|integer|min:1',
            'status' => 'sometimes|required|in:aktif,nonaktif',
            'keterangan' => 'nullable|string',
            'fasilitas_ids' => 'nullable|array',
            'fasilitas_ids.*' => 'exists:fasilitas,id',
            'jadwals' => 'nullable|array',
            'jadwals.*.sopir_id' => 'required_with:jadwals|exists:sopir,id',
            'jadwals.*.rute_id' => 'required_with:jadwals|exists:rute,id',
            'jadwals.*.tanggal_berangkat' => 'required_with:jadwals|date',
            'jadwals.*.jam_berangkat' => 'required_with:jadwals|date_format:H:i',
            'jadwals.*.status' => 'required_with:jadwals|in:aktif,nonaktif',
        ]);

        DB::transaction(function () use ($request, $bus) {
            $bus->update($request->only(['nama', 'plat_nomor', 'kapasitas', 'status', 'keterangan']));

            if ($request->has('fasilitas_ids')) {
                $bus->fasilitas()->sync($request->fasilitas_ids);
            }

            if ($request->has('jadwals')) {
                // Hapus jadwal lama dan buat ulang
                $bus->jadwals()->delete();
                foreach ($request->jadwals as $jadwalData) {
                    $jadwalData['bus_id'] = $bus->id;
                    Jadwal::create($jadwalData);
                }
            }
        });

        return response()->json($bus);
    }

    // Delete a specific bus
    public function destroy($id)
    {
        $bus = Bus::findOrFail($id);
        $bus->delete();
        return response()->json(['message' => 'Bus deleted successfully']);
    }
}