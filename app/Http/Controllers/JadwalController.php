<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Bus;
use App\Models\Sopir;
use App\Models\Rute;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class JadwalController extends Controller
{
    public function index(Request $request): \Illuminate\View\View
    {
        $search = $request->input("search");
        $dateFrom = $request->input("date_from");
        $dateTo = $request->input("date_to");
        $user = auth()->user();
        $userRole = $user->roles->first()?->name;

        $jadwals = QueryBuilder::for(Jadwal::with("bus", "sopir.user", "conductor.user", "rute.asalTerminal", "rute.tujuanTerminal"))
            ->where(function ($q) use ($search, $userRole, $user) {
                if ($userRole === "agent" && $user->terminal_id) {
                    $q->whereHas("rute", function ($q2) use ($user) {
                        $q2->where("asal_terminal_id", $user->terminal_id);
                    });
                }
                if ($search) {
                    $q->whereHas("bus", function ($q2) use ($search) {
                        $q2->where("nama", "like", "%{$search}%")->orWhere("plat_nomor", "like", "%{$search}%");
                    })
                        ->orWhereHas("sopir.user", function ($q2) use ($search) {
                            $q2->where("name", "like", "%{$search}%");
                        })
                        ->orWhereHas("rute.asalTerminal", function ($q2) use ($search) {
                            $q2->where("nama_terminal", "like", "%{$search}%")->orWhere("nama_kota", "like", "%{$search}%");
                        })
                        ->orWhereHas("rute.tujuanTerminal", function ($q2) use ($search) {
                            $q2->where("nama_terminal", "like", "%{$search}%")->orWhere("nama_kota", "like", "%{$search}%");
                        })
                        ->orWhere("tanggal_berangkat", "like", "%{$search}%")
                        ->orWhere("status", "like", "%{$search}%");
                }
            })
            ->when($dateFrom, function ($query) use ($dateFrom) {
                return $query->whereDate("created_at", ">=", $dateFrom);
            })
            ->when($dateTo, function ($query) use ($dateTo) {
                return $query->whereDate("created_at", "<=", $dateTo);
            })
            ->allowedSorts(["tanggal_berangkat", "status", "created_at"])
            ->defaultSort("-created_at")
            ->paginate(10)
            ->withQueryString();

        $sort = $request->input("sort", "-created_at");
        $order = strpos($sort, "-") === 0 ? "desc" : "asc";
        $sortField = ltrim($sort, "-");

        return view("jadwal.index", compact("jadwals", "search", "sort", "order", "sortField", "dateFrom", "dateTo"));
    }

    public function create(): \Illuminate\View\View
    {
        $buses = Bus::all();
        $sopirs = Sopir::with("user")->where("status", "aktif")->get();
        $conductors = Sopir::with("user")->where("status", "aktif")->get();
        $rutes = Rute::with("asalTerminal", "tujuanTerminal")->get();
        $kelasBuses = \App\Models\KelasBus::all();
        return view("jadwal.create", compact("buses", "sopirs", "conductors", "rutes", "kelasBuses"));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            "bus_id" => "required|exists:bus,id",
            "sopir_id" => "required|exists:sopir,id",
            "conductor_id" => "nullable|exists:sopir,id",
            "rute_id" => "required|exists:rute,id",
            "tanggal_berangkat" => "required|date_format:Y-m-d",
            "jam_berangkat" => "required|date_format:H:i",
            "status" => "required|in:aktif,tidak_aktif",
            "is_recurring" => "nullable|boolean",
            "recurring_type" => "nullable|in:daily,weekly",
            "recurring_count" => "nullable|integer|min:1|max:90",
            "harga" => "nullable|array",
            "harga.*" => "nullable|numeric|min:0",
        ]);

        if ($request->is_recurring) {
            $jadwals = [];
            $tanggal = Carbon::parse($request->tanggal_berangkat);
            $interval = $request->recurring_type === "weekly" ? 7 : 1;

            for ($i = 0; $i < $request->recurring_count; $i++) {
                $jadwals[] = [
                    "bus_id" => $request->bus_id,
                    "sopir_id" => $request->sopir_id,
                    "conductor_id" => $request->conductor_id,
                    "rute_id" => $request->rute_id,
                    "tanggal_berangkat" => $tanggal->toDateString(),
                    "jam_berangkat" => $request->jam_berangkat,
                    "status" => $request->status,
                    "created_at" => now(),
                    "updated_at" => now(),
                ];
                $tanggal->addDays($interval);
            }

            $createdJadwals = Jadwal::insert($jadwals);

            // Add pricing for recurring schedules if provided
            if ($request->has("harga") && is_array($request->harga)) {
                $jadwalsData = Jadwal::where("bus_id", $request->bus_id)->where("rute_id", $request->rute_id)->where("tanggal_berangkat", ">=", $request->tanggal_berangkat)->orderBy("tanggal_berangkat")->limit($request->recurring_count)->get();

                foreach ($jadwalsData as $jadwal) {
                    $this->addPricingToJadwal($jadwal, $request->harga);
                }
            }

            $message = count($jadwals) . " jadwal berhasil ditambahkan";
        } else {
            $jadwal = Jadwal::create($request->only(["bus_id", "sopir_id", "conductor_id", "rute_id", "tanggal_berangkat", "jam_berangkat", "status"]));

            // Add pricing if provided
            if ($request->has("harga") && is_array($request->harga)) {
                $this->addPricingToJadwal($jadwal, $request->harga);
            }

            $message = "Jadwal berhasil ditambahkan";
        }

        return redirect()->route("admin/jadwal.index")->with("success", $message);
    }

    private function addPricingToJadwal(Jadwal $jadwal, array $harga)
    {
        foreach ($harga as $kelasBusId => $hargaValue) {
            if ($hargaValue !== null && $hargaValue !== "") {
                \App\Models\JadwalKelasBus::firstOrCreate(
                    [
                        "jadwal_id" => $jadwal->id,
                        "kelas_bus_id" => $kelasBusId,
                    ],
                    [
                        "harga" => $hargaValue,
                    ],
                );
            }
        }
    }

    public function show(Jadwal $jadwal): \Illuminate\View\View
    {
        $jadwal->load("bus", "sopir.user", "conductor.user", "rute.asalTerminal", "rute.tujuanTerminal");
        return view("jadwal.show", compact("jadwal"));
    }

    public function edit(Jadwal $jadwal): \Illuminate\View\View
    {
        $buses = Bus::all();
        $sopirs = Sopir::with("user")->where("status", "aktif")->get();
        $conductors = Sopir::with("user")->where("status", "aktif")->get();
        $rutes = Rute::with("asalTerminal", "tujuanTerminal")->get();
        $kelasBuses = \App\Models\KelasBus::all();
        $jadwalKelasBuses = $jadwal->jadwalKelasBus()->get();
        return view("jadwal.edit", compact("jadwal", "buses", "sopirs", "conductors", "rutes", "kelasBuses", "jadwalKelasBuses"));
    }

    public function update(Request $request, Jadwal $jadwal): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            "bus_id" => "required|exists:bus,id",
            "sopir_id" => "required|exists:sopir,id",
            "conductor_id" => "nullable|exists:sopir,id",
            "rute_id" => "required|exists:rute,id",
            "tanggal_berangkat" => "required|date",
            "jam_berangkat" => "required|date_format:H:i",
            "status" => "required|in:aktif,tidak_aktif",
            "harga" => "nullable|array",
            "harga.*" => "nullable|numeric|min:0",
        ]);

        $jadwal->update($request->all());

        // Update pricing if provided
        if ($request->has("harga") && is_array($request->harga)) {
            $this->addPricingToJadwal($jadwal, $request->harga);
        }

        return redirect()->route("admin/jadwal.index")->with("success", "Jadwal berhasil diperbarui");
    }

    public function destroy(Jadwal $jadwal): \Illuminate\Http\RedirectResponse
    {
        $jadwal->delete();

        return redirect()->route("admin/jadwal.index")->with("success", "Jadwal berhasil dihapus");
    }
}
