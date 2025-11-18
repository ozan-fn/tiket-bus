<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusFasilitas;
use Illuminate\Http\Request;

class BusFasilitasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $busFasilitas = BusFasilitas::paginate($perPage);

        return response()->json($busFasilitas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bus_id' => 'required|exists:bus,id',
            'fasilitas_id' => 'required|exists:fasilitas,id',
        ]);

        $busFasilitas = BusFasilitas::create($validated);

        return response()->json($busFasilitas, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(BusFasilitas $busFasilitas)
    {
        return response()->json($busFasilitas);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BusFasilitas $busFasilitas)
    {
        $validated = $request->validate([
            'bus_id' => 'sometimes|required|exists:bus,id',
            'fasilitas_id' => 'sometimes|required|exists:fasilitas,id',
        ]);

        $busFasilitas->update($validated);

        return response()->json($busFasilitas);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BusFasilitas $busFasilitas)
    {
        $busFasilitas->delete();

        return response()->json(null, 204);
    }
}