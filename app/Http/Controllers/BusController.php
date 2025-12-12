<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\BusPhoto;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\QueryBuilder;

class BusController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input("search");
        $dateFrom = $request->input("date_from");
        $dateTo = $request->input("date_to");

        $bus = QueryBuilder::for(Bus::with("fasilitas", "photos"))
            ->where(function ($q) use ($search) {
                if ($search) {
                    $q->where("nama", "like", "%{$search}%")->orWhere("plat_nomor", "like", "%{$search}%");
                }
            })
            ->when($dateFrom, function ($query) use ($dateFrom) {
                return $query->whereDate("created_at", ">=", $dateFrom);
            })
            ->when($dateTo, function ($query) use ($dateTo) {
                return $query->whereDate("created_at", "<=", $dateTo);
            })
            ->allowedSorts(["nama", "plat_nomor", "kapasitas", "status", "created_at"])
            ->defaultSort("-created_at")
            ->paginate(10)
            ->withQueryString();

        $sort = $request->input("sort", "-created_at");
        $order = strpos($sort, "-") === 0 ? "desc" : "asc";
        $sortField = ltrim($sort, "-");

        return view("bus.index", compact("bus", "search", "sort", "order", "sortField", "dateFrom", "dateTo"));
    }

    public function create(): View
    {
        $fasilitas = \App\Models\Fasilitas::all();
        return view("bus.create", compact("fasilitas"));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            "nama" => "required|string|max:255",
            "kapasitas" => "required|integer|min:1",
            "plat_nomor" => "required|string|max:255|unique:bus",
            "fasilitas_ids" => "array",
            "fasilitas_ids.*" => "exists:fasilitas,id",
            "foto" => "array",
            "foto.*" => "image|mimes:jpeg,png,jpg,gif|max:2048",
        ]);

        $bus = Bus::create($request->only(["nama", "kapasitas", "plat_nomor"]));
        $bus->fasilitas()->sync($request->fasilitas_ids ?? []);

        if ($request->hasFile("foto")) {
            foreach ($request->file("foto") as $file) {
                $path = $file->store("bus_foto", "public");
                BusPhoto::create([
                    "bus_id" => $bus->id,
                    "path" => $path,
                ]);
            }
        }

        return redirect()->route("admin/bus.index")->with("success", "Bus berhasil ditambahkan");
    }

    public function show(Bus $bus): View
    {
        $bus->load("fasilitas", "photos");
        return view("bus.show", compact("bus"));
    }

    public function edit(Bus $bus): View
    {
        $fasilitas = \App\Models\Fasilitas::all();
        $bus->load("fasilitas", "photos");
        return view("bus.edit", compact("bus", "fasilitas"));
    }

    public function update(Request $request, Bus $bus): RedirectResponse
    {
        $request->validate([
            "nama" => "required|string|max:255",
            "kapasitas" => "required|integer|min:1",
            "plat_nomor" => "required|string|max:255|unique:bus,plat_nomor," . $bus->id,
            "fasilitas_ids" => "array",
            "fasilitas_ids.*" => "exists:fasilitas,id",
            "foto" => "array",
            "foto.*" => "image|mimes:jpeg,png,jpg,gif|max:2048",
        ]);

        $bus->update($request->only(["nama", "kapasitas", "plat_nomor"]));
        $bus->fasilitas()->sync($request->fasilitas_ids ?? []);

        if ($request->hasFile("foto")) {
            // Upload foto baru (foto lama tetap ada)
            foreach ($request->file("foto") as $file) {
                $path = $file->store("bus_foto", "public");
                BusPhoto::create([
                    "bus_id" => $bus->id,
                    "path" => $path,
                ]);
            }
        }

        return redirect()->route("admin/bus.index")->with("success", "Bus berhasil diperbarui");
    }

    public function destroy(Bus $bus): RedirectResponse
    {
        // Hapus foto
        foreach ($bus->photos as $photo) {
            Storage::disk("public")->delete($photo->path);
            $photo->delete();
        }

        $bus->delete();

        return redirect()->route("admin/bus.index")->with("success", "Bus berhasil dihapus");
    }

    public function destroyPhoto(BusPhoto $busPhoto)
    {
        try {
            // Hapus file jika ada
            if ($busPhoto->path && Storage::disk("public")->exists($busPhoto->path)) {
                Storage::disk("public")->delete($busPhoto->path);
            }

            // Hapus dari database
            $busPhoto->delete();

            return response()->json([
                "success" => true,
                "message" => "Foto berhasil dihapus",
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Gagal menghapus foto: " . $e->getMessage(),
                ],
                500,
            );
        }
    }
}
