<?php

namespace App\Http\Controllers;

use App\Models\Rute;
use App\Models\Terminal;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RuteController extends Controller
{
    public function index(): View
    {
        $rutes = Rute::with('asalTerminal', 'tujuanTerminal')->paginate(10);
        return view('rute.index', compact('rutes'));
    }

    public function create(): View
    {
        $terminals = Terminal::all();
        return view('rute.create', compact('terminals'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'asal_terminal_id' => 'required|exists:terminal,id',
            'tujuan_terminal_id' => 'required|exists:terminal,id|different:asal_terminal_id',
        ]);

        Rute::create($request->only(['asal_terminal_id', 'tujuan_terminal_id']));

        return redirect()->route('admin/rute.index')->with('success', 'Rute berhasil ditambahkan');
    }

    public function show(Rute $rute): View
    {
        $rute->load('asalTerminal', 'tujuanTerminal');
        return view('rute.show', compact('rute'));
    }

    public function edit(Rute $rute): View
    {
        $terminals = Terminal::all();
        return view('rute.edit', compact('rute', 'terminals'));
    }

    public function update(Request $request, Rute $rute): RedirectResponse
    {
        $request->validate([
            'asal_terminal_id' => 'required|exists:terminal,id',
            'tujuan_terminal_id' => 'required|exists:terminal,id|different:asal_terminal_id',
        ]);

        $rute->update($request->only(['asal_terminal_id', 'tujuan_terminal_id']));

        return redirect()->route('admin/rute.index')->with('success', 'Rute berhasil diperbarui');
    }

    public function destroy(Rute $rute): RedirectResponse
    {
        $rute->delete();

        return redirect()->route('admin/rute.index')->with('success', 'Rute berhasil dihapus');
    }
}
