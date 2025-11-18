<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\BusPhoto;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BusController extends Controller
{
    public function index(): View
    {
        $bus = Bus::with('fasilitas', 'photos')->paginate(10);
        return view('bus.index', compact('bus'));
    }

    public function create(): View
    {
        $fasilitas = \App\Models\Fasilitas::all();
        return view('bus.create', compact('fasilitas'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
            'plat_nomor' => 'required|string|max:255|unique:bus',
            'fasilitas_ids' => 'array',
            'fasilitas_ids.*' => 'exists:fasilitas,id',
            'foto' => 'array',
            'foto.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $bus = Bus::create($request->only(['nama', 'kapasitas', 'plat_nomor']));
        $bus->fasilitas()->sync($request->fasilitas_ids ?? []);

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('bus_foto', 'public');
                BusPhoto::create([
                    'bus_id' => $bus->id,
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('admin/bus.index')->with('success', 'Bus berhasil ditambahkan');
    }

    public function show(Bus $bus): View
    {
        $bus->load('fasilitas', 'photos');
        return view('bus.show', compact('bus'));
    }

    public function edit(Bus $bus): View
    {
        $fasilitas = \App\Models\Fasilitas::all();
        $bus->load('fasilitas', 'photos');
        return view('bus.edit', compact('bus', 'fasilitas'));
    }

    public function update(Request $request, Bus $bus): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
            'plat_nomor' => 'required|string|max:255|unique:bus,plat_nomor,' . $bus->id,
            'fasilitas_ids' => 'array',
            'fasilitas_ids.*' => 'exists:fasilitas,id',
            'foto' => 'array',
            'foto.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $bus->update($request->only(['nama', 'kapasitas', 'plat_nomor']));
        $bus->fasilitas()->sync($request->fasilitas_ids ?? []);

        if ($request->hasFile('foto')) {
            // Hapus foto lama
            foreach ($bus->photos as $photo) {
                \Storage::disk('public')->delete($photo->path);
                $photo->delete();
            }
            // Upload foto baru
            foreach ($request->file('foto') as $file) {
                $path = $file->store('bus_foto', 'public');
                BusPhoto::create([
                    'bus_id' => $bus->id,
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('admin/bus.index')->with('success', 'Bus berhasil diperbarui');
    }

    public function destroy(Bus $bus): RedirectResponse
    {
        // Hapus foto
        foreach ($bus->photos as $photo) {
            \Storage::disk('public')->delete($photo->path);
            $photo->delete();
        }

        $bus->delete();

        return redirect()->route('admin/bus.index')->with('success', 'Bus berhasil dihapus');
    }
}