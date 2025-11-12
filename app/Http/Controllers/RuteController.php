<?php

namespace App\Http\Controllers;

use App\Models\Rute;
use Illuminate\Http\Request;

class RuteController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $rute = Rute::with(['asalTerminal', 'tujuanTerminal'])->paginate($perPage);
        return response()->json($rute);
    }

    public function store(Request $request)
    {
        $request->validate([
            'asal_terminal_id' => 'required|exists:terminal,id',
            'tujuan_terminal_id' => 'required|exists:terminal,id',
        ]);
        $rute = Rute::create($request->all());
        return response()->json($rute, 201);
    }

    public function show($id)
    {
        $rute = Rute::with(['asalTerminal', 'tujuanTerminal'])->findOrFail($id);
        return response()->json($rute);
    }

    public function update(Request $request, $id)
    {
        $rute = Rute::findOrFail($id);
        $rute->update($request->all());
        return response()->json($rute);
    }

    public function destroy($id)
    {
        $rute = Rute::findOrFail($id);
        $rute->delete();
        return response()->json(['message' => 'Rute dihapus']);
    }
}
