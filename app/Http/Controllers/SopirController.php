<?php

namespace App\Http\Controllers;

use App\Models\Sopir;
use App\Models\User;
use App\Models\Jadwal;
use App\Models\Tiket;
use App\Models\Kursi;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use Carbon\Carbon;

class SopirController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input("search");
        $dateFrom = $request->input("date_from");
        $dateTo = $request->input("date_to");

        $sopir = QueryBuilder::for(Sopir::with("user"))
            ->where(function ($q) use ($search) {
                if ($search) {
                    $q->where("nik", "like", "%{$search}%")
                        ->orWhere("nomor_sim", "like", "%{$search}%")
                        ->orWhere("alamat", "like", "%{$search}%")
                        ->orWhere("telepon", "like", "%{$search}%")
                        ->orWhereHas("user", function ($q2) use ($search) {
                            $q2->where("name", "like", "%{$search}%")->orWhere("email", "like", "%{$search}%");
                        });
                }
            })
            ->when($dateFrom, function ($query) use ($dateFrom) {
                return $query->whereDate("created_at", ">=", $dateFrom);
            })
            ->when($dateTo, function ($query) use ($dateTo) {
                return $query->whereDate("created_at", "<=", $dateTo);
            })
            ->allowedSorts(["nik", "nomor_sim", "created_at"])
            ->defaultSort("-created_at")
            ->paginate(10)
            ->withQueryString();

        $sort = $request->input("sort", "-created_at");
        $order = strpos($sort, "-") === 0 ? "desc" : "asc";
        $sortField = ltrim($sort, "-");

        return view("sopir.index", compact("sopir", "search", "sort", "order", "sortField", "dateFrom", "dateTo"));
    }

    public function create(): View
    {
        return view("sopir.create");
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|email|max:255|unique:users",
            "password" => "required|string|min:8|confirmed",
            "nik" => "required|string|max:255|unique:sopir",
            "nomor_sim" => "required|string|max:255|unique:sopir",
            "alamat" => "nullable|string",
            "telepon" => "nullable|string|max:255",
            "tanggal_lahir" => "required|date",
            "status" => "required|in:aktif,tidak_aktif",
        ]);

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password),
        ]);

        Sopir::create([
            "nik" => $request->nik,
            "nomor_sim" => $request->nomor_sim,
            "alamat" => $request->alamat,
            "telepon" => $request->telepon,
            "tanggal_lahir" => $request->tanggal_lahir,
            "status" => $request->status,
        ]);

        return redirect()->route("admin/sopir.index")->with("success", "Sopir berhasil ditambahkan");
    }

    public function show(Sopir $sopir): View
    {
        $sopir->load("user");
        return view("sopir.show", compact("sopir"));
    }

    public function edit(Sopir $sopir): View
    {
        return view("sopir.edit", compact("sopir"));
    }

    public function update(Request $request, Sopir $sopir): RedirectResponse
    {
        $request->validate([
            "nik" => "required|string|max:255|unique:sopir,nik," . $sopir->id,
            "nomor_sim" => "required|string|max:255|unique:sopir,nomor_sim," . $sopir->id,
            "alamat" => "nullable|string",
            "telepon" => "nullable|string|max:255",
            "tanggal_lahir" => "required|date",
            "status" => "required|in:aktif,tidak_aktif",
        ]);

        $sopir->update([
            "nik" => $request->nik,
            "nomor_sim" => $request->nomor_sim,
            "alamat" => $request->alamat,
            "telepon" => $request->telepon,
            "tanggal_lahir" => $request->tanggal_lahir,
            "status" => $request->status,
        ]);

        return redirect()->route("admin/sopir.index")->with("success", "Sopir berhasil diperbarui");
    }

    public function destroy(Sopir $sopir): RedirectResponse
    {
        $sopir->delete();

        return redirect()->route("admin/sopir.index")->with("success", "Sopir berhasil dihapus");
    }

    public function searchUsers(Request $request)
    {
        $query = $request->get("q", "");
        $users = User::where(function ($q) use ($query) {
            if ($query) {
                $q->where("name", "like", "%{$query}%")->orWhere("email", "like", "%{$query}%");
            }
        })
            ->limit($query ? 20 : 10) // Limit lebih sedikit jika default
            ->get(["id", "name", "email"]);

        return response()->json([
            "results" => $users->map(function ($user) {
                return [
                    "value" => $user->id,
                    "text" => $user->name . " (" . $user->email . ")",
                ];
            }),
        ]);
    }

    public function showJadwal(Jadwal $jadwal): View
    {
        $user = Auth::user();
        $sopir = $user->sopir;

        // Validasi jadwal milik sopir
        if ($jadwal->sopir_id !== $sopir->id) {
            abort(403, "Unauthorized");
        }

        $jadwal->load([
            "bus",
            "rute",
            "jadwalKelasBus" => function ($query) {
                $query->with([
                    "kelasBus",
                    "kursi" => function ($q) {
                        $q->with([
                            "tikets" => function ($t) {
                                $t->where("status", "dibayar");
                            }
                        ]);
                    }
                ]);
            }
        ]);

        // Hitung statistik kursi
        $kursiStats = [
            "total" => 0,
            "dipesan" => 0,
            "kosong" => 0,
            "hadir" => 0,
            "tidak_hadir" => 0,
        ];

        foreach ($jadwal->jadwalKelasBus as $jkb) {
            foreach ($jkb->kursi as $kursi) {
                $kursiStats["total"]++;

                $tiketAktif = $kursi->tikets->first();
                if ($tiketAktif) {
                    if ($tiketAktif->is_hadir) {
                        $kursiStats["hadir"]++;
                    } else {
                        $kursiStats["dipesan"]++;
                    }
                } else {
                    $kursiStats["kosong"]++;
                }
            }
        }

        return view("sopir.jadwal-detail", compact("jadwal", "kursiStats"));
    }

    public function scanTiket(Request $request, Jadwal $jadwal): JsonResponse
    {
        $user = Auth::user();
        $sopir = $user->sopir;

        // Validasi jadwal milik sopir
        if ($jadwal->sopir_id !== $sopir->id) {
            return response()->json(["success" => false, "message" => "Unauthorized"], 403);
        }

        $request->validate([
            "kode_tiket" => "required|string",
        ]);

        $tiket = Tiket::where("kode_tiket", $request->kode_tiket)
            ->where("status", "dibayar")
            ->with(["jadwalKelasBus", "kursi"])
            ->first();

        if (!$tiket) {
            return response()->json([
                "success" => false,
                "message" => "Tiket tidak ditemukan atau belum dibayar",
            ]);
        }

        // Validasi tiket untuk jadwal ini
        if ($tiket->jadwalKelasBus->jadwal_id !== $jadwal->id) {
            return response()->json([
                "success" => false,
                "message" => "Tiket tidak sesuai dengan jadwal ini",
            ]);
        }

        // Jika sudah hadir, beri konfirmasi
        if ($tiket->is_hadir) {
            return response()->json([
                "success" => true,
                "message" => "Tiket sudah di-scan sebelumnya",
                "tiket" => [
                    "id" => $tiket->id,
                    "nama_penumpang" => $tiket->nama_penumpang,
                    "kode_tiket" => $tiket->kode_tiket,
                    "nomor_kursi" => $tiket->kursi?->nomor_kursi,
                    "is_hadir" => $tiket->is_hadir,
                    "waktu_scan" => $tiket->waktu_scan?->format("H:i:s"),
                ],
                "already_scanned" => true,
            ]);
        }

        // Update tiket: set is_hadir = true dan waktu_scan
        $tiket->update([
            "is_hadir" => true,
            "waktu_scan" => now(),
        ]);

        return response()->json([
            "success" => true,
            "message" => "Tiket berhasil di-scan",
            "tiket" => [
                "id" => $tiket->id,
                "nama_penumpang" => $tiket->nama_penumpang,
                "kode_tiket" => $tiket->kode_tiket,
                "nomor_kursi" => $tiket->kursi?->nomor_kursi,
                "is_hadir" => $tiket->is_hadir,
                "waktu_scan" => $tiket->waktu_scan?->format("H:i:s"),
            ],
        ]);
    }

    public function getKursiStatus(Jadwal $jadwal): JsonResponse
    {
        $user = Auth::user();
        $sopir = $user->sopir;

        if ($jadwal->sopir_id !== $sopir->id) {
            return response()->json(["success" => false, "message" => "Unauthorized"], 403);
        }

        $jadwal->load([
            "jadwalKelasBus" => function ($query) {
                $query->with([
                    "kelasBus",
                    "kursi" => function ($q) {
                        $q->with([
                            "tikets" => function ($t) {
                                $t->where("status", "dibayar");
                            }
                        ]);
                    }
                ]);
            }
        ]);

        $kursiData = [];
        foreach ($jadwal->jadwalKelasBus as $jkb) {
            foreach ($jkb->kursi as $kursi) {
                $tiketAktif = $kursi->tikets->first();
                $status = "kosong";
                $penumpang = null;

                if ($tiketAktif) {
                    $status = $tiketAktif->is_hadir ? "hadir" : "dipesan";
                    $penumpang = [
                        "nama" => $tiketAktif->nama_penumpang,
                        "kode_tiket" => $tiketAktif->kode_tiket,
                        "is_hadir" => $tiketAktif->is_hadir,
                    ];
                }

                $kursiData[] = [
                    "id" => $kursi->id,
                    "nomor" => $kursi->nomor_kursi,
                    "kelas" => $jkb->kelasBus->nama,
                    "status" => $status,
                    "penumpang" => $penumpang,
                ];
            }
        }

        return response()->json([
            "success" => true,
            "kursi" => $kursiData,
        ]);
    }
    // SCAN TIKET - Driver Interface
    public function scanIndex(): View
    {
        $user = Auth::user();
        $sopir = $user->sopir;

        // Get driver's active jadwals
        $jadwals = Jadwal::where("sopir_id", $sopir->id)
            ->where("status", "aktif")
            ->orderBy("tanggal_berangkat", "desc")
            ->orderBy("jam_berangkat", "desc")
            ->with(["bus", "rute"])
            ->get();

        return view("sopir.scan", compact("jadwals"));
    }

    public function scanVerify(Request $request): JsonResponse
    {
        $user = Auth::user();
        $sopir = $user->sopir;

        $request->validate([
            "kode_tiket" => "required|string",
        ]);

        $tiket = Tiket::with(["jadwalKelasBus.jadwal", "kursi"])
            ->where("kode_tiket", $request->kode_tiket)
            ->first();

        if (!$tiket) {
            return response()->json([
                "success" => false,
                "message" => "Kode tiket tidak ditemukan",
            ], 404);
        }

        // Validate tiket belongs to driver's jadwal
        if ($tiket->jadwalKelasBus->jadwal->sopir_id !== $sopir->id) {
            return response()->json([
                "success" => false,
                "message" => "Tiket tidak sesuai dengan jadwal Anda",
            ], 403);
        }

        // Check status
        if ($tiket->status === "batal") {
            return response()->json([
                "success" => false,
                "message" => "Tiket telah dibatalkan",
                "tiket" => [
                    "kode_tiket" => $tiket->kode_tiket,
                    "nama_penumpang" => $tiket->nama_penumpang,
                    "nomor_kursi" => $tiket->kursi?->nomor_kursi ?? "N/A",
                    "is_hadir" => $tiket->is_hadir,
                ],
            ], 400);
        }

        if ($tiket->status === "dipesan") {
            return response()->json([
                "success" => false,
                "message" => "Tiket belum dibayar",
                "tiket" => [
                    "kode_tiket" => $tiket->kode_tiket,
                    "nama_penumpang" => $tiket->nama_penumpang,
                    "nomor_kursi" => $tiket->kursi?->nomor_kursi ?? "N/A",
                    "is_hadir" => $tiket->is_hadir,
                ],
            ], 400);
        }

        // Check expiry
        $jadwal = $tiket->jadwalKelasBus->jadwal;
        if ($jadwal) {
            $waktuBerangkat = Carbon::parse($jadwal->tanggal_berangkat->format("Y-m-d") . " " . $jadwal->jam_berangkat->format("H:i:s"));
            if ($waktuBerangkat->isPast()) {
                return response()->json([
                    "success" => false,
                    "message" => "Tiket telah expired",
                    "tiket" => [
                        "kode_tiket" => $tiket->kode_tiket,
                        "nama_penumpang" => $tiket->nama_penumpang,
                        "nomor_kursi" => $tiket->kursi?->nomor_kursi ?? "N/A",
                        "is_hadir" => $tiket->is_hadir,
                    ],
                ], 400);
            }
        }

        // If already scanned
        if ($tiket->is_hadir) {
            return response()->json([
                "success" => true,
                "message" => "Tiket sudah di-scan sebelumnya",
                "tiket" => [
                    "kode_tiket" => $tiket->kode_tiket,
                    "nama_penumpang" => $tiket->nama_penumpang,
                    "nomor_kursi" => $tiket->kursi?->nomor_kursi ?? "N/A",
                    "is_hadir" => $tiket->is_hadir,
                    "waktu_scan" => $tiket->waktu_scan?->format("H:i:s"),
                ],
                "already_scanned" => true,
            ]);
        }

        // Mark as scanned
        $tiket->update([
            "is_hadir" => true,
            "waktu_scan" => now(),
        ]);

        return response()->json([
            "success" => true,
            "message" => "Tiket berhasil di-scan",
            "tiket" => [
                "kode_tiket" => $tiket->kode_tiket,
                "nama_penumpang" => $tiket->nama_penumpang,
                "nomor_kursi" => $tiket->kursi?->nomor_kursi ?? "N/A",
                "is_hadir" => $tiket->is_hadir,
                "waktu_scan" => $tiket->waktu_scan?->format("H:i:s"),
            ],
        ]);
    }

    // CEK KURSI - Driver Interface
    public function cekKursiIndex(): View
    {
        $user = Auth::user();
        $sopir = $user->sopir;

        // Get driver's jadwals
        $jadwals = Jadwal::where("sopir_id", $sopir->id)
            ->where("status", "aktif")
            ->orderBy("tanggal_berangkat", "desc")
            ->with(["bus", "rute", "conductor.user", "jadwalKelasBus.kelasBus"])
            ->paginate(5);

        return view("sopir.cek-kursi", compact("jadwals"));
    }

    public function cekKursiGet(Request $request): JsonResponse
    {
        $user = Auth::user();
        $sopir = $user->sopir;

        try {
            $jadwalId = $request->input("jadwal_id");

            if (!$jadwalId) {
                return response()->json([
                    "success" => false,
                    "message" => "ID jadwal tidak ditemukan",
                ], 400);
            }

            // Get jadwal and verify it belongs to driver
            $jadwal = Jadwal::with([
                "bus",
                "rute.asalTerminal",
                "rute.tujuanTerminal",
                "jadwalKelasBus.kelasBus.kursi",
                "jadwalKelasBus.tikets"
            ])->find($jadwalId);

            if (!$jadwal || $jadwal->sopir_id !== $sopir->id) {
                return response()->json([
                    "success" => false,
                    "message" => "Jadwal tidak ditemukan",
                ], 404);
            }

            // Build kursi data
            $kursiData = [];
            foreach ($jadwal->jadwalKelasBus as $jkb) {
                foreach ($jkb->kelasBus->kursi as $kursi) {
                    $bookedTicket = $jkb->tikets->where("kursi_id", $kursi->id)->first();
                    $kursiData[] = [
                        "id" => $kursi->id,
                        "nomor_kursi" => $kursi->nomor_kursi,
                        "status" => $bookedTicket ? "booked" : "available",
                        "kelas" => $jkb->kelasBus->nama_kelas,
                    ];
                }
            }

            // Count stats
            $totalKursi = count($kursiData);
            $bookedKursi = collect($kursiData)->where("status", "booked")->count();
            $availableKursi = $totalKursi - $bookedKursi;

            return response()->json([
                "success" => true,
                "jadwal" => [
                    "bus_nama" => $jadwal->bus->nama,
                    "bus_plat" => $jadwal->bus->plat_nomor,
                    "tanggal_berangkat" => $jadwal->tanggal_berangkat->format("d M Y"),
                    "jam_berangkat" => $jadwal->jam_berangkat->format("H:i"),
                    "asal_terminal" => $jadwal->rute->asalTerminal->nama_terminal,
                    "tujuan_terminal" => $jadwal->rute->tujuanTerminal->nama_terminal,
                ],
                "kursi" => $kursiData,
                "kursi_summary" => [
                    "total" => $totalKursi,
                    "booked" => $bookedKursi,
                    "available" => $availableKursi,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Terjadi kesalahan: " . $e->getMessage(),
            ], 500);
        }
    }
}
