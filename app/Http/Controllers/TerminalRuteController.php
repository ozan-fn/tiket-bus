<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Terminal;
use App\Models\Rute;

class TerminalRuteController extends Controller
{
    // Menampilkan data terminal beserta rute yang melewati terminal tersebut
    public function index(Request $request)
    {
        $query = Terminal::with(['ruteTerminals.rute']);
        if ($request->has('rute_id')) {
            $query->whereHas('ruteTerminals', function ($q) use ($request) {
                $q->where('rute_id', $request->rute_id);
            });
        }
        $terminals = $query->get();
        return response()->json($terminals);
    }

    // Menampilkan rute beserta terminal yang dilewati
    public function ruteTerminals($id)
    {
        $rute = Rute::with(['ruteTerminals.terminal'])->findOrFail($id);
        return response()->json($rute);
    }
}
