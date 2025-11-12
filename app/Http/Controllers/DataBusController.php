<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bus;

class DataBusController extends Controller
{
    // Menampilkan data bus beserta fasilitas dan status armada
    public function index(Request $request)
    {
        $query = Bus::with(['fasilitas']);
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        $bus = $query->get();
        return response()->json($bus);
    }

    // Detail bus
    public function show($id)
    {
        $bus = Bus::with(['fasilitas'])->findOrFail($id);
        return response()->json($bus);
    }
}
