<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Bus;
use App\Models\Sopir;
use App\Models\Rute;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class JadwalController extends Controller
{
    public function index(Request $request): \Illuminate\View\View
    {
        // Update status jadwal expired jadi tidak_aktif
        Jadwal::where("status", "aktif")
            ->where(function ($q) {
                $q->whereDate("tanggal_berangkat", "<", now()->toDateString())->orWhere(function ($q2) {
                    $q2->whereDate("tanggal_berangkat", now()->toDateString())->whereTime("jam_berangkat", "<=", now()->toTimeString());
                });
            })
            ->update(["status" => "tidak_aktif"]);

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
                        ->orWhereHas("sopir", function ($q2) use ($search) {
                            $q2->whereHas("user", function ($q3) use ($search) {
                                $q3->where("name", "like", "%{$search}%");
                            });
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
            ->orderByRaw("CASE WHEN status = 'aktif' THEN 1 ELSE 2 END")
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

        return view("jadwal.create", compact("buses", "sopirs", "conductors", "rutes"));
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

        // Validate that bus has the kelas bus for pricing
        if ($request->has("harga") && is_array($request->harga)) {
            foreach ($request->harga as $kelasBusId => $hargaValue) {
                if ($hargaValue !== null && $hargaValue !== "") {
                    $busKelasBus = \App\Models\BusKelasBus::where("kelas_bus_id", $kelasBusId)->where("bus_id", $request->bus_id)->first();
                    if (!$busKelasBus) {
                        return back()
                            ->withErrors(["harga.{$kelasBusId}" => "Kelas bus ini tidak tersedia untuk bus yang dipilih."])
                            ->withInput();
                    }
                }
            }
        }

        return DB::transaction(function () use ($request) {
            if ($request->is_recurring) {
                $jadwals = [];
                $tanggal = Carbon::parse($request->tanggal_berangkat);
                $interval = $request->recurring_type === "weekly" ? 7 : 1;

                for ($i = 0; $i < $request->recurring_count; $i++) {
                    $jadwal = Jadwal::create([
                        "bus_id" => $request->bus_id,
                        "sopir_id" => $request->sopir_id,
                        "conductor_id" => $request->conductor_id,
                        "rute_id" => $request->rute_id,
                        "tanggal_berangkat" => $tanggal->toDateString(),
                        "jam_berangkat" => $request->jam_berangkat,
                        "status" => $request->status,
                    ]);

                    // Add pricing for this jadwal if provided
                    if ($request->has("harga") && is_array($request->harga)) {
                        $this->addPricingToJadwal($jadwal, $request->harga);
                    }

                    $tanggal->addDays($interval);
                }

                $message = $request->recurring_count . " jadwal berhasil ditambahkan";
            } else {
                $jadwal = Jadwal::create($request->only(["bus_id", "sopir_id", "conductor_id", "rute_id", "tanggal_berangkat", "jam_berangkat", "status"]));

                // Add pricing if provided
                if ($request->has("harga") && is_array($request->harga)) {
                    $this->addPricingToJadwal($jadwal, $request->harga);
                }

                $message = "Jadwal berhasil ditambahkan";
            }

            return redirect()->route("admin/jadwal.index")->with("success", $message);
        });
    }

    private function addPricingToJadwal(Jadwal $jadwal, array $harga)
    {
        try {
            foreach ($harga as $kelasBusId => $hargaValue) {
                if ($hargaValue !== null && $hargaValue !== "") {
                    // Find bus_kelas_bus_id for this kelas_bus_id and bus_id
                    $busKelasBus = \App\Models\BusKelasBus::where("kelas_bus_id", $kelasBusId)->where("bus_id", $jadwal->bus_id)->first();

                    if ($busKelasBus) {
                        $jadwalKelasBus = \App\Models\JadwalKelasBus::updateOrCreate(
                            [
                                "jadwal_id" => $jadwal->id,
                                "bus_kelas_bus_id" => $busKelasBus->id,
                            ],
                            [
                                "harga" => $hargaValue,
                            ],
                        );
                    } else {
                        // Log if bus_kelas_bus not found
                    }
                }
            }
        } catch (\Exception $e) {
            throw $e; // Re-throw to rollback transaction
        }
    }

    public function show(Jadwal $jadwal): \Illuminate\View\View
    {
        $jadwal->load("bus", "sopir.user", "conductor.user", "rute.asalTerminal", "rute.tujuanTerminal", "jadwalKelasBus.kelasBus");
        return view("jadwal.show", compact("jadwal"));
    }

    public function edit(Jadwal $jadwal): \Illuminate\View\View
    {
        $buses = Bus::all();
        $sopirs = Sopir::with("user")->where("status", "aktif")->get();
        $conductors = Sopir::with("user")->where("status", "aktif")->get();
        $rutes = Rute::with("asalTerminal", "tujuanTerminal")->get();
        $jadwal_id = $jadwal->id;
        return view("jadwal.edit", compact("jadwal", "buses", "sopirs", "conductors", "rutes", "jadwal_id"));
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

        return DB::transaction(function () use ($request, $jadwal) {
            $jadwal->update($request->only(["bus_id", "sopir_id", "conductor_id", "rute_id", "tanggal_berangkat", "jam_berangkat", "status"]));

            // Delete existing pricing
            $jadwal->jadwalKelasBus()->delete();

            // Add new pricing if provided
            if ($request->has("harga") && is_array($request->harga)) {
                $this->addPricingToJadwal($jadwal, $request->harga);
            }

            return redirect()->route("admin/jadwal.index")->with("success", "Jadwal berhasil diperbarui");
        });
    }

    public function destroy(Jadwal $jadwal): \Illuminate\Http\RedirectResponse
    {
        $jadwal->delete();

        return redirect()->route("admin/jadwal.index")->with("success", "Jadwal berhasil dihapus");
    }

    public function getKelasByBus(Request $request, Bus $bus): \Illuminate\Http\JsonResponse
    {
        $jadwalId = $request->input("jadwal_id");
        $kelasBuses = $bus->kelasBus->map(function ($kelasBus) use ($jadwalId, $bus) {
            $busKelasBus = \App\Models\BusKelasBus::where("bus_id", $bus->id)->where("kelas_bus_id", $kelasBus->id)->first();
            $jadwalKelasBus = $jadwalId && $busKelasBus ? \App\Models\JadwalKelasBus::where("jadwal_id", $jadwalId)->where("bus_kelas_bus_id", $busKelasBus->id)->first() : null;
            $kelasBus->harga = $jadwalKelasBus ? $jadwalKelasBus["harga"] : null;
            return $kelasBus;
        });

        return response()->json($kelasBuses);
    }
}
