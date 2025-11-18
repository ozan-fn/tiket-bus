<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Terminal;
use Illuminate\Http\Request;

class TerminalController extends Controller
{
    public function index(Request $request)
    {
        $query = Terminal::query();

        // Conditional eager loading based on 'include' query parameter
        if ($request->has('include')) {
            $includes = explode(',', $request->include);
            // Filter to only allowed relations for security
            $allowed = ['rutes', 'rute'];
            $validIncludes = array_intersect($includes, $allowed);
            if (!empty($validIncludes)) {
                $query->with($validIncludes);
            }
        }

        $perPage = $request->get('per_page', 10);
        $terminal = $query->paginate($perPage);
        return response()->json($terminal);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_terminal' => 'required|string',
            'nama_kota' => 'required|string',
            'alamat' => 'required|string',
        ]);
        $terminal = Terminal::create($request->all());
        return response()->json($terminal, 201);
    }

    public function show(Request $request, $id)
    {
        $query = Terminal::query();

        // Conditional eager loading based on 'include' query parameter
        if ($request->has('include')) {
            $includes = explode(',', $request->include);
            // Filter to only allowed relations for security
            $allowed = ['rutes', 'rute'];
            $validIncludes = array_intersect($includes, $allowed);
            if (!empty($validIncludes)) {
                $query->with($validIncludes);
            }
        }

        $terminal = $query->findOrFail($id);
        return response()->json($terminal);
    }

    public function update(Request $request, $id)
    {
        $terminal = Terminal::findOrFail($id);
        $terminal->update($request->all());
        return response()->json($terminal);
    }

    public function destroy($id)
    {
        $terminal = Terminal::findOrFail($id);
        $terminal->delete();
        return response()->json(['message' => 'Terminal dihapus']);
    }
}