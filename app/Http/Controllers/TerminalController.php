<?php

namespace App\Http\Controllers;

use App\Models\Terminal;
use Illuminate\Http\Request;

class TerminalController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $terminal = Terminal::paginate($perPage);
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

    public function show($id)
    {
        $terminal = Terminal::findOrFail($id);
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
