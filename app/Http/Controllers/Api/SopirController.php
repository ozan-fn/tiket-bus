<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sopir;
use Illuminate\Http\Request;

class SopirController extends Controller
{
    public function index(Request $request)
    {
        $query = Sopir::query();

        // Conditional eager loading based on 'include' query parameter
        if ($request->has('include')) {
            $includes = explode(',', $request->include);
            // Filter to only allowed relations for security
            $allowed = ['user'];
            $validIncludes = array_intersect($includes, $allowed);
            if (!empty($validIncludes)) {
                $query->with($validIncludes);
            }
        }

        $perPage = $request->get('per_page', 10);
        $sopir = $query->paginate($perPage);
        return response()->json($sopir);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nik' => 'required|string',
            'nomor_sim' => 'required|string',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string',
            'tanggal_lahir' => 'required|date',
            'status' => 'required|string',
        ]);
        $sopir = Sopir::create($request->all());
        return response()->json($sopir, 201);
    }

    public function show(Request $request, $id)
    {
        $query = Sopir::query();

        // Conditional eager loading based on 'include' query parameter
        if ($request->has('include')) {
            $includes = explode(',', $request->include);
            // Filter to only allowed relations for security
            $allowed = ['user'];
            $validIncludes = array_intersect($includes, $allowed);
            if (!empty($validIncludes)) {
                $query->with($validIncludes);
            }
        }

        $sopir = $query->findOrFail($id);
        return response()->json($sopir);
    }

    public function update(Request $request, $id)
    {
        $sopir = Sopir::findOrFail($id);
        $sopir->update($request->all());
        return response()->json($sopir);
    }

    public function destroy($id)
    {
        $sopir = Sopir::findOrFail($id);
        $sopir->delete();
        return response()->json(['message' => 'Sopir dihapus']);
    }
}