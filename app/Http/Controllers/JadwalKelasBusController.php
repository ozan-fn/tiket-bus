<?php

namespace App\Http\Controllers;

use App\Models\JadwalKelasBus;
use App\Models\Jadwal;
use App\Models\KelasBus;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class JadwalKelasBusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input("search");
        $dateFrom = $request->input("date_from");
        $dateTo = $request->input("date_to");

        $jadwalKelasBus = QueryBuilder::for(JadwalKelasBus::with(["jadwal.bus", "jadwal.rute.asalTerminal", "jadwal.rute.tujuanTerminal", "kelasBus"]))
            ->where(function ($q) use ($search) {
                if ($search) {
                    $q->whereHas("jadwal.bus", function ($q2) use ($search) {
                        $q2->where("nama", "like", "%{$search}%")->orWhere("plat_nomor", "like", "%{$search}%");
                    })
                        ->orWhereHas("jadwal.rute.asalTerminal", function ($q2) use ($search) {
                            $q2->where("nama_terminal", "like", "%{$search}%")->orWhere("nama_kota", "like", "%{$search}%");
                        })
                        ->orWhereHas("jadwal.rute.tujuanTerminal", function ($q2) use ($search) {
                            $q2->where("nama_terminal", "like", "%{$search}%")->orWhere("nama_kota", "like", "%{$search}%");
                        })
                        ->orWhereHas("kelasBus", function ($q2) use ($search) {
                            $q2->where("nama_kelas", "like", "%{$search}%");
                        })
                        ->orWhere("harga", "like", "%{$search}%");
                }
            })
            ->when($dateFrom, function ($query) use ($dateFrom) {
                return $query->whereDate("created_at", ">=", $dateFrom);
            })
            ->when($dateTo, function ($query) use ($dateTo) {
                return $query->whereDate("created_at", "<=", $dateTo);
            })
            ->allowedSorts(["harga", "created_at"])
            ->defaultSort("-created_at")
            ->paginate(15)
            ->withQueryString();

        $sort = $request->input("sort", "-created_at");
        $order = strpos($sort, "-") === 0 ? "desc" : "asc";
        $sortField = ltrim($sort, "-");

        return view("jadwal-kelas-bus.index", compact("jadwalKelasBus", "search", "sort", "order", "sortField", "dateFrom", "dateTo"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jadwals = Jadwal::with(["bus", "rute.asalTerminal", "rute.tujuanTerminal"])
            ->where("tanggal_berangkat", ">=", now())
            ->get();

        $kelasBuses = KelasBus::with("bus")->get();

        return view("jadwal-kelas-bus.create", compact("jadwals", "kelasBuses"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "jadwal_id" => "required|exists:jadwal,id",
            "kelas_bus_id" => "required|exists:kelas_bus,id",
            "harga" => "required|numeric|min:0",
        ]);

        // Check if combination already exists
        $exists = JadwalKelasBus::where("jadwal_id", $validated["jadwal_id"])->where("kelas_bus_id", $validated["kelas_bus_id"])->exists();

        if ($exists) {
            return redirect()->back()->withInput()->with("error", "Kombinasi jadwal dan kelas bus sudah ada");
        }

        JadwalKelasBus::create($validated);

        return redirect()->route("admin/jadwal-kelas-bus.index")->with("success", "Jadwal kelas bus berhasil ditambahkan");
    }

    /**
     * Display the specified resource.
     */
    public function show(JadwalKelasBus $jadwalKelasBu)
    {
        $jadwalKelasBu->load(["jadwal.bus", "jadwal.sopir.user", "jadwal.rute.asalTerminal", "jadwal.rute.tujuanTerminal", "kelasBus.bus", "tikets.kursi", "tikets.user"]);

        return view("jadwal-kelas-bus.show", compact("jadwalKelasBu"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JadwalKelasBus $jadwalKelasBu)
    {
        $jadwals = Jadwal::with(["bus", "rute.asalTerminal", "rute.tujuanTerminal"])->get();
        $kelasBuses = KelasBus::with("bus")->get();

        return view("jadwal-kelas-bus.edit", compact("jadwalKelasBu", "jadwals", "kelasBuses"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JadwalKelasBus $jadwalKelasBu)
    {
        $validated = $request->validate([
            "jadwal_id" => "required|exists:jadwal,id",
            "kelas_bus_id" => "required|exists:kelas_bus,id",
            "harga" => "required|numeric|min:0",
        ]);

        // Check if combination already exists (excluding current record)
        $exists = JadwalKelasBus::where("jadwal_id", $validated["jadwal_id"])->where("kelas_bus_id", $validated["kelas_bus_id"])->where("id", "!=", $jadwalKelasBu->id)->exists();

        if ($exists) {
            return redirect()->back()->withInput()->with("error", "Kombinasi jadwal dan kelas bus sudah ada");
        }

        $jadwalKelasBu->update($validated);

        return redirect()->route("admin/jadwal-kelas-bus.index")->with("success", "Jadwal kelas bus berhasil diperbarui");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JadwalKelasBus $jadwalKelasBu)
    {
        // Check if has tiket
        if ($jadwalKelasBu->tikets()->count() > 0) {
            return redirect()->back()->with("error", "Tidak dapat menghapus jadwal kelas bus yang sudah memiliki tiket");
        }

        $jadwalKelasBu->delete();

        return redirect()->route("admin/jadwal-kelas-bus.index")->with("success", "Jadwal kelas bus berhasil dihapus");
    }

    /**
     * Get kelas bus by jadwal
     */
    public function getKelasByJadwal($jadwal_id)
    {
        $kelasBuses = KelasBus::whereHas("bus.jadwal", function ($query) use ($jadwal_id) {
            $query->where("id", $jadwal_id);
        })->get();

        return response()->json($kelasBuses);
    }
}
