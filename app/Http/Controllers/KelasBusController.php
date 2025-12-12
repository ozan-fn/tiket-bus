<?php

namespace App\Http\Controllers;

use App\Models\KelasBus;
use App\Models\Bus;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class KelasBusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input("search");
        $dateFrom = $request->input("date_from");
        $dateTo = $request->input("date_to");

        $kelasBus = QueryBuilder::for(KelasBus::with("bus"))
            ->where(function ($q) use ($search) {
                if ($search) {
                    $q->where("nama_kelas", "like", "%{$search}%")
                        ->orWhere("harga", "like", "%{$search}%")
                        ->orWhereHas("bus", function ($q2) use ($search) {
                            $q2->where("nama", "like", "%{$search}%")->orWhere("plat_nomor", "like", "%{$search}%");
                        });
                }
            })
            ->when($dateFrom, function ($query) use ($dateFrom) {
                return $query->whereDate("created_at", ">=", $dateFrom);
            })
            ->when($dateTo, function ($query) use ($dateTo) {
                return $query->whereDate("created_at", "<=", $dateTo);
            })
            ->allowedSorts(["nama_kelas", "harga", "created_at"])
            ->defaultSort("-created_at")
            ->paginate(10)
            ->withQueryString();

        $sort = $request->input("sort", "-created_at");
        $order = strpos($sort, "-") === 0 ? "desc" : "asc";
        $sortField = ltrim($sort, "-");

        return view("kelas-bus.index", compact("kelasBus", "search", "sort", "order", "sortField", "dateFrom", "dateTo"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $buses = Bus::all();
        return view("kelas-bus.create", compact("buses"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "bus_id" => "required|exists:bus,id",
            "nama_kelas" => "required|string|max:50",
            "deskripsi" => "nullable|string",
            "jumlah_kursi" => "required|integer|min:1",
        ]);

        KelasBus::create($validated);

        return redirect()->route("admin/kelas-bus.index")->with("success", "Kelas bus berhasil ditambahkan");
    }

    /**
     * Display the specified resource.
     */
    public function show(KelasBus $kelasBus)
    {
        $kelasBus->load("bus", "kursi");
        return view("kelas-bus.show", compact("kelasBus"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KelasBus $kelasBus)
    {
        $buses = Bus::all();
        return view("kelas-bus.edit", compact("kelasBus", "buses"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KelasBus $kelasBus)
    {
        $validated = $request->validate([
            "bus_id" => "required|exists:bus,id",
            "nama_kelas" => "required|string|max:50",
            "deskripsi" => "nullable|string",
            "jumlah_kursi" => "required|integer|min:1",
        ]);

        $kelasBus->update($validated);

        return redirect()->route("admin/kelas-bus.index")->with("success", "Kelas bus berhasil diperbarui");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KelasBus $kelasBus)
    {
        $kelasBus->delete();

        return redirect()->route("admin/kelas-bus.index")->with("success", "Kelas bus berhasil dihapus");
    }
}
