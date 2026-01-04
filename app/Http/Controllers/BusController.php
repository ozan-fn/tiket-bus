<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\BusPhoto;
use App\Models\KelasBus;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\DB;

class BusController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input("search");
        $dateFrom = $request->input("date_from");
        $dateTo = $request->input("date_to");

        $bus = QueryBuilder::for(Bus::with("fasilitas", "photos", "kelasBus"))
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
        $kelasBus = \App\Models\KelasBus::all();
        return view("bus.create", compact("fasilitas", "kelasBus"));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            "nama" => "required|string|max:255",
            "kapasitas" => "required|integer|min:1",
            "plat_nomor" => "required|string|max:255|unique:bus",
            "fasilitas_ids" => "array",
            "fasilitas_ids.*" => "exists:fasilitas,id",
            "kelas_bus_data" => "array",
            "kelas_bus_data.*.kelas_id" => "required|exists:kelas_bus,id",
            "kelas_bus_data.*.jumlah_kursi" => "required|integer|min:1",
            "foto" => "array",
            "foto.*" => "nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5048",
        ]);

        // Check total seats must exactly equal capacity
        $kapasitas = intval($request->kapasitas);
        $totalKursi = 0;
        if ($request->has("kelas_bus_data") && is_array($request->kelas_bus_data)) {
            foreach ($request->kelas_bus_data as $kelasData) {
                $totalKursi += intval($kelasData["jumlah_kursi"]);
            }
        }

        if ($totalKursi !== $kapasitas) {
            return back()
                ->withErrors(["kelas_bus_data" => "Total kursi kelas bus harus sama dengan kapasitas bus."])
                ->withInput();
        }

        try {
            $bus = DB::transaction(function () use ($request) {
                $bus = Bus::create($request->only(["nama", "kapasitas", "plat_nomor"]));
                $bus->fasilitas()->sync($request->fasilitas_ids ?? []);

                // Simpan kelas bus yang dipilih
                if ($request->has("kelas_bus_data") && is_array($request->kelas_bus_data)) {
                    foreach ($request->kelas_bus_data as $kelasData) {
                        // Disimpan sebagai relasi pivot dengan jumlah kursi
                        $bus->kelasBus()->attach($kelasData["kelas_id"], [
                            "jumlah_kursi" => $kelasData["jumlah_kursi"],
                        ]);
                    }
                }

                // Ensure kursi rows exist for each bus_kelas_bus (create if missing)
                $bus->load("busKelasBus.kursi");
                foreach ($bus->busKelasBus as $bkb) {
                    if ($bkb->kursi->isEmpty() && intval($bkb->jumlah_kursi) > 0) {
                        for ($i = 1; $i <= intval($bkb->jumlah_kursi); $i++) {
                            \App\Models\Kursi::create([
                                "bus_kelas_bus_id" => $bkb->id,
                                "nomor_kursi" => $i,
                                "index" => $i - 1,
                            ]);
                        }
                    }
                }

                if ($request->hasFile("foto")) {
                    foreach ($request->file("foto") as $file) {
                        $path = $file->store("bus_foto", "public");
                        BusPhoto::create([
                            "bus_id" => $bus->id,
                            "path" => $path,
                        ]);
                    }
                }

                // Verifikasi tambahan: pastikan total di DB sesuai kapasitas sebelum commit
                $dbTotal = $bus->busKelasBus()->sum("jumlah_kursi");
                if (intval($dbTotal) !== intval($bus->kapasitas)) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        "kelas_bus_data" => ["Total kursi setelah penyimpanan tidak sama dengan kapasitas bus."],
                    ]);
                }

                return $bus;
            });
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return back()->withErrors($ve->errors())->withInput();
        }

        return redirect()->route("admin/bus.index")->with("success", "Bus berhasil ditambahkan");
    }

    public function show(Bus $bus): View
    {
        $bus->load("fasilitas", "photos", "kelasBus");
        return view("bus.show", compact("bus"));
    }

    public function edit(Bus $bus): View
    {
        $fasilitas = \App\Models\Fasilitas::all();
        $kelasBus = \App\Models\KelasBus::all();
        $bus->load("fasilitas", "photos", "kelasBus");
        return view("bus.edit", compact("bus", "fasilitas", "kelasBus"));
    }

    public function update(Request $request, Bus $bus): RedirectResponse
    {
        $request->validate([
            "nama" => "required|string|max:255",
            "kapasitas" => "required|integer|min:1",
            "plat_nomor" => "required|string|max:255|unique:bus,plat_nomor," . $bus->id,
            "fasilitas_ids" => "array",
            "fasilitas_ids.*" => "exists:fasilitas,id",
            "kelas_bus_data" => "array",
            "kelas_bus_data.*.kelas_id" => "required|exists:kelas_bus,id",
            "kelas_bus_data.*.jumlah_kursi" => "required|integer|min:1",
            "foto" => "array",
            "foto.*" => "image|mimes:jpeg,png,jpg,gif|max:2048",
        ]);

        // Check total seats must exactly equal capacity (based on input if provided)
        $kapasitas = intval($request->kapasitas);
        $totalKursi = 0;
        if ($request->has("kelas_bus_data") && is_array($request->kelas_bus_data)) {
            foreach ($request->kelas_bus_data as $kelasData) {
                $totalKursi += intval($kelasData["jumlah_kursi"]);
            }
        } else {
            // if no kelas_bus_data provided, calculate from existing pivot
            $bus->load("kelasBus");
            foreach ($bus->kelasBus as $kb) {
                $pivotJumlah = 0;
                if (isset($kb->pivot) && isset($kb->pivot->jumlah_kursi)) {
                    $pivotJumlah = intval($kb->pivot->jumlah_kursi);
                }
                $totalKursi += $pivotJumlah;
            }
        }

        if ($totalKursi !== $kapasitas) {
            return back()
                ->withErrors(["kelas_bus_data" => "Total kursi kelas bus harus sama dengan kapasitas bus."])
                ->withInput();
        }

        try {
            DB::transaction(function () use ($request, $bus) {
                $bus->update($request->only(["nama", "kapasitas", "plat_nomor"]));
                $bus->fasilitas()->sync($request->fasilitas_ids ?? []);

                // Sinkronisasi kelas bus
                $kelasBusData = [];
                if ($request->has("kelas_bus_data") && is_array($request->kelas_bus_data)) {
                    foreach ($request->kelas_bus_data as $kelasData) {
                        $kelasBusData[$kelasData["kelas_id"]] = [
                            "jumlah_kursi" => $kelasData["jumlah_kursi"],
                        ];
                    }
                }
                $bus->kelasBus()->sync($kelasBusData);

                // Ensure kursi rows exist for each bus_kelas_bus (create if missing)
                $bus->load("busKelasBus.kursi");
                foreach ($bus->busKelasBus as $bkb) {
                    if ($bkb->kursi->isEmpty() && intval($bkb->jumlah_kursi) > 0) {
                        for ($i = 1; $i <= intval($bkb->jumlah_kursi); $i++) {
                            \App\Models\Kursi::create([
                                "bus_kelas_bus_id" => $bkb->id,
                                "nomor_kursi" => $i,
                                "index" => $i - 1,
                            ]);
                        }
                    }
                }

                if ($request->hasFile("foto")) {
                    foreach ($request->file("foto") as $file) {
                        $path = $file->store("bus_foto", "public");
                        BusPhoto::create([
                            "bus_id" => $bus->id,
                            "path" => $path,
                        ]);
                    }
                }

                // Verifikasi setelah sync: total di DB harus sama dengan kapasitas ter-update
                $bus->load("kelasBus");
                $totalAfterSync = 0;
                foreach ($bus->kelasBus as $kb) {
                    $pivotJumlah = 0;
                    if (isset($kb->pivot) && isset($kb->pivot->jumlah_kursi)) {
                        $pivotJumlah = intval($kb->pivot->jumlah_kursi);
                    }
                    $totalAfterSync += $pivotJumlah;
                }

                $currentKapasitas = intval($bus->kapasitas);
                if ($totalAfterSync !== $currentKapasitas) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        "kelas_bus_data" => ["Total kursi setelah sinkronisasi tidak sama dengan kapasitas bus."],
                    ]);
                }
            });
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return back()->withErrors($ve->errors())->withInput();
        }

        return redirect()->route("admin/bus.index")->with("success", "Bus berhasil diperbarui");
    }

    public function destroy(Bus $bus): RedirectResponse
    {
        if ($bus->jadwals()->exists()) {
            return redirect()->back()->with("error", "Bus tidak dapat dihapus karena masih terkait dengan jadwal atau kelas bus.");
        }

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
