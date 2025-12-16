<?php

namespace App\Http\Controllers;

use App\Models\KelasBus;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Spatie\QueryBuilder\QueryBuilder;

class KelasBusController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input("search");
        $dateFrom = $request->input("date_from");
        $dateTo = $request->input("date_to");

        $kelasBus = QueryBuilder::for(KelasBus::class)
            ->where(function ($q) use ($search) {
                if ($search) {
                    $q->where("nama_kelas", "like", "%{$search}%");
                }
            })
            ->when($dateFrom, function ($query) use ($dateFrom) {
                return $query->whereDate("created_at", ">=", $dateFrom);
            })
            ->when($dateTo, function ($query) use ($dateTo) {
                return $query->whereDate("created_at", "<=", $dateTo);
            })
            ->allowedSorts(["nama_kelas", "jumlah_kursi", "created_at"])
            ->defaultSort("-created_at")
            ->paginate(10)
            ->withQueryString();

        $sort = $request->input("sort", "-created_at");
        $order = strpos($sort, "-") === 0 ? "desc" : "asc";
        $sortField = ltrim($sort, "-");

        return view("kelas-bus.index", compact("kelasBus", "search", "sort", "order", "sortField", "dateFrom", "dateTo"));
    }

    public function create(): View
    {
        return view("kelas-bus.create");
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            "nama_kelas" => "required|string|max:255",
            "deskripsi" => "nullable|string",
        ]);

        KelasBus::create($request->only("nama_kelas", "deskripsi"));

        return redirect()->route("admin/kelas-bus.index")->with("success", "Kelas bus berhasil ditambahkan");
    }

    public function show(KelasBus $kelasBus): View
    {
        return view("kelas-bus.show", compact("kelasBus"));
    }

    public function edit(KelasBus $kelasBus): View
    {
        return view("kelas-bus.edit", compact("kelasBus"));
    }

    public function update(Request $request, KelasBus $kelasBus): RedirectResponse
    {
        $request->validate([
            "nama_kelas" => "required|string|max:255",
            "deskripsi" => "nullable|string",
        ]);

        $kelasBus->update($request->only("nama_kelas", "deskripsi"));

        return redirect()->route("admin/kelas-bus.index")->with("success", "Kelas bus berhasil diperbarui");
    }

    public function destroy(KelasBus $kelasBus): RedirectResponse
    {
        $kelasBus->delete();

        return redirect()->route("admin/kelas-bus.index")->with("success", "Kelas bus berhasil dihapus");
    }
}
