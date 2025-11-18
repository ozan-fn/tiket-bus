<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class FasilitasController extends Controller
{
    public function index(): View
    {
        $fasilitas = Fasilitas::paginate(10);
        return view('fasilitas.index', compact('fasilitas'));
    }

    public function create(): View
    {
        return view('fasilitas.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        Fasilitas::create($request->only('nama'));

        return redirect()->route('admin/fasilitas.index')->with('success', 'Fasilitas berhasil ditambahkan');
    }

    public function show(Fasilitas $fasilitas): View
    {
        return view('fasilitas.show', compact('fasilitas'));
    }

    public function edit(Fasilitas $fasilitas): View
    {
        return view('fasilitas.edit', compact('fasilitas'));
    }

    public function update(Request $request, Fasilitas $fasilitas): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $fasilitas->update($request->only('nama'));

        return redirect()->route('admin/fasilitas.index')->with('success', 'Fasilitas berhasil diperbarui');
    }

    public function destroy(Fasilitas $fasilitas): RedirectResponse
    {
        $fasilitas->delete();

        return redirect()->route('admin/fasilitas.index')->with('success', 'Fasilitas berhasil dihapus');
    }
}