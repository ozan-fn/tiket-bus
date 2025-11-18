<?php

namespace App\Http\Controllers;

use App\Models\Terminal;
use App\Models\TerminalPhoto;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TerminalController extends Controller
{
    public function index(): View
    {
        $terminals = Terminal::with('photos')->paginate(10);
        return view('terminal.index', compact('terminals'));
    }

    public function create(): View
    {
        return view('terminal.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_terminal' => 'required|string|max:255|unique:terminal',
            'nama_kota' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'foto' => 'array',
            'foto.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $terminal = Terminal::create($request->only(['nama_terminal', 'nama_kota', 'alamat']));

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('terminal_foto', 'public');
                TerminalPhoto::create([
                    'terminal_id' => $terminal->id,
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('admin/terminal.index')->with('success', 'Terminal berhasil ditambahkan');
    }

    public function show(Terminal $terminal): View
    {
        $terminal->load('photos');
        return view('terminal.show', compact('terminal'));
    }

    public function edit(Terminal $terminal): View
    {
        $terminal->load('photos');
        return view('terminal.edit', compact('terminal'));
    }

    public function update(Request $request, Terminal $terminal): RedirectResponse
    {
        $request->validate([
            'nama_terminal' => 'required|string|max:255|unique:terminal,nama_terminal,' . $terminal->id,
            'nama_kota' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'foto' => 'array',
            'foto.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $terminal->update($request->only(['nama_terminal', 'nama_kota', 'alamat']));

        if ($request->hasFile('foto')) {
            // Hapus foto lama
            foreach ($terminal->photos as $photo) {
                \Storage::disk('public')->delete($photo->path);
                $photo->delete();
            }
            // Upload foto baru
            foreach ($request->file('foto') as $file) {
                $path = $file->store('terminal_foto', 'public');
                TerminalPhoto::create([
                    'terminal_id' => $terminal->id,
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('admin/terminal.index')->with('success', 'Terminal berhasil diperbarui');
    }

    public function destroy(Terminal $terminal): RedirectResponse
    {
        // Hapus foto
        foreach ($terminal->photos as $photo) {
            \Storage::disk('public')->delete($photo->path);
            $photo->delete();
        }

        $terminal->delete();

        return redirect()->route('admin/terminal.index')->with('success', 'Terminal berhasil dihapus');
    }
}
