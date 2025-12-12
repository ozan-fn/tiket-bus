<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Spatie\QueryBuilder\QueryBuilder;

class FasilitasController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input("search");
        $dateFrom = $request->input("date_from");
        $dateTo = $request->input("date_to");

        $fasilitas = QueryBuilder::for(Fasilitas::class)
            ->where(function ($q) use ($search) {
                if ($search) {
                    $q->where("nama", "like", "%{$search}%");
                }
            })
            ->when($dateFrom, function ($query) use ($dateFrom) {
                return $query->whereDate("created_at", ">=", $dateFrom);
            })
            ->when($dateTo, function ($query) use ($dateTo) {
                return $query->whereDate("created_at", "<=", $dateTo);
            })
            ->allowedSorts(["nama", "created_at"])
            ->defaultSort("-created_at")
            ->paginate(10)
            ->withQueryString();

        $sort = $request->input("sort", "-created_at");
        $order = strpos($sort, "-") === 0 ? "desc" : "asc";
        $sortField = ltrim($sort, "-");

        return view("fasilitas.index", compact("fasilitas", "search", "sort", "order", "sortField", "dateFrom", "dateTo"));
    }

    public function create(): View
    {
        return view("fasilitas.create");
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            "nama" => "required|string|max:255",
        ]);

        Fasilitas::create($request->only("nama"));

        return redirect()->route("admin/fasilitas.index")->with("success", "Fasilitas berhasil ditambahkan");
    }

    public function show(Fasilitas $fasilitas): View
    {
        return view("fasilitas.show", compact("fasilitas"));
    }

    public function edit(Fasilitas $fasilitas): View
    {
        return view("fasilitas.edit", compact("fasilitas"));
    }

    public function update(Request $request, Fasilitas $fasilitas): RedirectResponse
    {
        $request->validate([
            "nama" => "required|string|max:255",
        ]);

        $fasilitas->update($request->only("nama"));

        return redirect()->route("admin/fasilitas.index")->with("success", "Fasilitas berhasil diperbarui");
    }

    public function destroy(Fasilitas $fasilitas): RedirectResponse
    {
        $fasilitas->delete();

        return redirect()->route("admin/fasilitas.index")->with("success", "Fasilitas berhasil dihapus");
    }
}
