<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tiket;
use App\Models\Kursi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TiketController extends Controller
{
    /**
     * Buat pemesanan tiket baru
     * POST /api/tiket
     */
    public function store(Request $request)
    {
        $request->validate([
            "jadwal_kelas_bus_id" => "required|exists:jadwal_kelas_bus,id",
            "kursi_id" => "required|exists:kursi,id",
        ]);

        // Ambil data user yang login
        $user = auth()->user();

        // Validasi: pastikan profil user sudah lengkap
        if (!$user->name || !$user->nik || !$user->jenis_kelamin || !$user->nomor_telepon || !$user->email) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Profil Anda belum lengkap. Silakan lengkapi profil terlebih dahulu sebelum memesan tiket.",
                    "missing_fields" => [
                        "name" => !$user->name,
                        "nik" => !$user->nik,
                        "jenis_kelamin" => !$user->jenis_kelamin,
                        "nomor_telepon" => !$user->nomor_telepon,
                        "email" => !$user->email,
                    ],
                ],
                422,
            );
        }

        // Get jadwal dari jadwal_kelas_bus
        $jadwalKelasBus = \App\Models\JadwalKelasBus::with("jadwal")->findOrFail($request->jadwal_kelas_bus_id);

        // Cek kursi masih tersedia atau tidak
        $kursiTerpakai = Tiket::where("jadwal_kelas_bus_id", $request->jadwal_kelas_bus_id)
            ->where("kursi_id", $request->kursi_id)
            ->whereIn("status", ["dipesan", "dibayar"])
            ->exists();

        if ($kursiTerpakai) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Kursi sudah dipesan oleh penumpang lain",
                ],
                409,
            );
        }

        // Generate kode tiket unik
        $kodeTicket = "TKT-" . strtoupper(Str::random(8));

        $tiket = Tiket::create([
            "user_id" => auth()->id(),
            "jadwal_kelas_bus_id" => $request->jadwal_kelas_bus_id,
            "kursi_id" => $request->kursi_id,
            "nama_penumpang" => $user->name,
            "nik" => $user->nik,
            "tanggal_lahir" => $user->tanggal_lahir,
            "jenis_kelamin" => $user->jenis_kelamin,
            "nomor_telepon" => $user->nomor_telepon,
            "email" => $user->email,
            "kode_tiket" => $kodeTicket,
            "harga" => $jadwalKelasBus->harga,
            "status" => "dipesan",
            "waktu_pesan" => now(),
        ]);

        $tiket->load(["jadwalKelasBus.jadwal.bus", "jadwalKelasBus.jadwal.rute.asalTerminal", "jadwalKelasBus.jadwal.rute.tujuanTerminal", "jadwalKelasBus.kelasBus", "kursi"]);

        return response()->json(
            [
                "success" => true,
                "message" => "Tiket berhasil dipesan",
                "data" => [
                    "id" => $tiket->id,
                    "kode_tiket" => $tiket->kode_tiket,
                    "nama_penumpang" => $tiket->nama_penumpang,
                    "nik" => $tiket->nik,
                    "email" => $tiket->email,
                    "nomor_telepon" => $tiket->nomor_telepon,
                    "jadwal" => [
                        "tanggal_berangkat" => $tiket->jadwalKelasBus->jadwal->tanggal_berangkat,
                        "jam_berangkat" => $tiket->jadwalKelasBus->jadwal->jam_berangkat,
                    ],
                    "rute" => [
                        "asal" => $tiket->jadwalKelasBus->jadwal->rute->asalTerminal->nama_terminal,
                        "tujuan" => $tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->nama_terminal,
                    ],
                    "bus" => [
                        "nama" => $tiket->jadwalKelasBus->jadwal->bus->nama,
                        "plat_nomor" => $tiket->jadwalKelasBus->jadwal->bus->plat_nomor,
                    ],
                    "kelas" => $tiket->jadwalKelasBus->kelasBus->nama_kelas,
                    "kursi" => [
                        "nomor" => $tiket->kursi->nomor_kursi,
                        "posisi" => $tiket->kursi->posisi,
                    ],
                    "harga" => $tiket->harga,
                    "status" => $tiket->status,
                    "waktu_pesan" => $tiket->waktu_pesan,
                ],
            ],
            201,
        );
    }

    /**
     * Get tiket user yang login
     * GET /api/tiket/my-tickets
     */
    public function myTickets()
    {
        $tikets = Tiket::with(["jadwalKelasBus.jadwal.bus", "jadwalKelasBus.jadwal.rute.asalTerminal", "jadwalKelasBus.jadwal.rute.tujuanTerminal", "jadwalKelasBus.kelasBus", "kursi"])
            ->where("user_id", auth()->id())
            ->orderBy("waktu_pesan", "desc")
            ->get();

        return response()->json([
            "success" => true,
            "data" => $tikets->map(
                fn($tiket) => [
                    "id" => $tiket->id,
                    "kode_tiket" => $tiket->kode_tiket,
                    "nama_penumpang" => $tiket->nama_penumpang,
                    "rute" => [
                        "asal" => $tiket->jadwalKelasBus->jadwal->rute->asalTerminal->nama_terminal,
                        "tujuan" => $tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->nama_terminal,
                    ],
                    "tanggal_berangkat" => $tiket->jadwalKelasBus->jadwal->tanggal_berangkat,
                    "jam_berangkat" => $tiket->jadwalKelasBus->jadwal->jam_berangkat,
                    "kelas" => $tiket->jadwalKelasBus->kelasBus->nama_kelas,
                    "kursi" => $tiket->kursi->nomor_kursi,
                    "harga" => $tiket->harga,
                    "status" => $tiket->status,
                    "waktu_pesan" => $tiket->waktu_pesan,
                ],
            ),
        ]);
    }

    /**
     * Get detail tiket by ID
     * GET /api/tiket/detail/{id}
     */
    public function showById($id)
    {
        $tiket = Tiket::with(["jadwalKelasBus.jadwal.bus.photos", "jadwalKelasBus.jadwal.sopir.user", "jadwalKelasBus.jadwal.rute.asalTerminal.photos", "jadwalKelasBus.jadwal.rute.tujuanTerminal.photos", "jadwalKelasBus.kelasBus", "kursi"])
            ->where("id", $id)
            ->where("user_id", auth()->id())
            ->firstOrFail();

        // Ambil pembayaran yang berhasil
        $pembayaranBerhasil = $tiket->pembayaran()->where("status", "dibayar")->first();

        return response()->json([
            "success" => true,
            "data" => [
                "id" => $tiket->id,
                "kode_tiket" => $tiket->kode_tiket,
                "nama_penumpang" => $tiket->nama_penumpang,
                "nik" => $tiket->nik,
                "jenis_kelamin" => $tiket->jenis_kelamin,
                "tanggal_lahir" => $tiket->tanggal_lahir,
                "nomor_telepon" => $tiket->nomor_telepon,
                "email" => $tiket->email,
                "bus" => [
                    "id" => $tiket->jadwalKelasBus->jadwal->bus->id,
                    "nama" => $tiket->jadwalKelasBus->jadwal->bus->nama,
                    "plat_nomor" => $tiket->jadwalKelasBus->jadwal->bus->plat_nomor,
                    "kapasitas" => $tiket->jadwalKelasBus->jadwal->bus->kapasitas,
                    "photos" => $tiket->jadwalKelasBus->jadwal->bus->photos
                        ->map(function ($photo) {
                            return asset("storage/" . $photo->path);
                        })
                        ->toArray(),
                ],
                "sopir" => [
                    "id" => $tiket->jadwalKelasBus->jadwal->sopir->id,
                    "nama" => $tiket->jadwalKelasBus->jadwal->sopir->user->name,
                    "photo" => $tiket->jadwalKelasBus->jadwal->sopir->user->photo ? asset("storage/" . $tiket->jadwalKelasBus->jadwal->sopir->user->photo) : null,
                ],
                "rute" => [
                    "id" => $tiket->jadwalKelasBus->jadwal->rute->id,
                    "asal" => [
                        "id" => $tiket->jadwalKelasBus->jadwal->rute->asalTerminal->id,
                        "nama" => $tiket->jadwalKelasBus->jadwal->rute->asalTerminal->nama_terminal,
                        "kota" => $tiket->jadwalKelasBus->jadwal->rute->asalTerminal->nama_kota,
                        "alamat" => $tiket->jadwalKelasBus->jadwal->rute->asalTerminal->alamat,
                        "photos" => $tiket->jadwalKelasBus->jadwal->rute->asalTerminal->photos
                            ->map(function ($photo) {
                                return asset("storage/" . $photo->path);
                            })
                            ->toArray(),
                    ],
                    "tujuan" => [
                        "id" => $tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->id,
                        "nama" => $tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->nama_terminal,
                        "kota" => $tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->nama_kota,
                        "alamat" => $tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->alamat,
                        "photos" => $tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->photos
                            ->map(function ($photo) {
                                return asset("storage/" . $photo->path);
                            })
                            ->toArray(),
                    ],
                ],
                "jadwal" => [
                    "id" => $tiket->jadwalKelasBus->jadwal->id,
                    "tanggal_berangkat" => $tiket->jadwalKelasBus->jadwal->tanggal_berangkat,
                    "jam_berangkat" => $tiket->jadwalKelasBus->jadwal->jam_berangkat,
                    "status" => $tiket->jadwalKelasBus->jadwal->status,
                ],
                "kelas" => [
                    "id" => $tiket->jadwalKelasBus->kelasBus->id,
                    "nama" => $tiket->jadwalKelasBus->kelasBus->nama_kelas,
                    "deskripsi" => $tiket->jadwalKelasBus->kelasBus->deskripsi,
                ],
                "kursi" => [
                    "id" => $tiket->kursi->id,
                    "nomor" => $tiket->kursi->nomor_kursi,
                    "posisi" => $tiket->kursi->posisi,
                ],
                "harga" => $tiket->harga,
                "status" => $tiket->status,
                "waktu_pesan" => $tiket->waktu_pesan,
                "pembayaran" => $pembayaranBerhasil
                    ? [
                        "id" => $pembayaranBerhasil->id,
                        "metode" => $pembayaranBerhasil->metode,
                        "status" => $pembayaranBerhasil->status,
                        "total_bayar" => $pembayaranBerhasil->nominal,
                        "waktu_bayar" => $pembayaranBerhasil->waktu_bayar,
                        "bukti_pembayaran" => $pembayaranBerhasil->bukti_pembayaran,
                    ]
                    : null,
            ],
        ]);
    }

    /**
     * Get detail tiket by kode tiket (Public)
     * GET /api/tiket/{kode_tiket}
     */
    public function show($kodeTicket)
    {
        $tiket = Tiket::with(["jadwalKelasBus.jadwal.bus.photos", "jadwalKelasBus.jadwal.sopir.user", "jadwalKelasBus.jadwal.rute.asalTerminal.photos", "jadwalKelasBus.jadwal.rute.tujuanTerminal.photos", "jadwalKelasBus.kelasBus", "kursi"])
            ->where("kode_tiket", $kodeTicket)
            ->firstOrFail();

        return response()->json([
            "success" => true,
            "data" => [
                "id" => $tiket->id,
                "kode_tiket" => $tiket->kode_tiket,
                "nama_penumpang" => $tiket->nama_penumpang,
                "nik" => $tiket->nik,
                "jenis_kelamin" => $tiket->jenis_kelamin,
                "nomor_telepon" => $tiket->nomor_telepon,
                "email" => $tiket->email,
                "bus" => [
                    "nama" => $tiket->jadwalKelasBus->jadwal->bus->nama,
                    "plat_nomor" => $tiket->jadwalKelasBus->jadwal->bus->plat_nomor,
                    "photos" => $tiket->jadwalKelasBus->jadwal->bus->photos
                        ->map(function ($photo) {
                            return asset("storage/" . $photo->path);
                        })
                        ->toArray(),
                ],
                "rute" => [
                    "asal" => [
                        "nama" => $tiket->jadwalKelasBus->jadwal->rute->asalTerminal->nama_terminal,
                        "photos" => $tiket->jadwalKelasBus->jadwal->rute->asalTerminal->photos
                            ->map(function ($photo) {
                                return asset("storage/" . $photo->path);
                            })
                            ->toArray(),
                    ],
                    "tujuan" => [
                        "nama" => $tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->nama_terminal,
                        "photos" => $tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->photos
                            ->map(function ($photo) {
                                return asset("storage/" . $photo->path);
                            })
                            ->toArray(),
                    ],
                ],
                "kelas" => $tiket->jadwalKelasBus->kelasBus->nama_kelas,
                "tanggal_berangkat" => $tiket->jadwalKelasBus->jadwal->tanggal_berangkat,
                "jam_berangkat" => $tiket->jadwalKelasBus->jadwal->jam_berangkat,
                "kursi" => [
                    "nomor" => $tiket->kursi->nomor_kursi,
                    "posisi" => $tiket->kursi->posisi,
                ],
                "harga" => $tiket->harga,
                "status" => $tiket->status,
                "waktu_pesan" => $tiket->waktu_pesan,
            ],
        ]);
    }

    /**
     * Verifikasi tiket oleh petugas (scan QR/input kode)
     * POST /api/tiket/{kode_tiket}/verify
     */
    public function verify(Request $request, $kodeTicket)
    {
        $isGet = $request->isMethod("get");

        // Log caller and attempt
        $callerId = auth()->check() ? auth()->id() : "guest";
        Log::info("verify called", ["kode_tiket" => $kodeTicket, "caller_id" => $callerId, "method" => $request->method()]);

        $tiket = Tiket::with(["jadwalKelasBus.jadwal.bus.photos", "jadwalKelasBus.jadwal.sopir", "jadwalKelasBus.jadwal.rute.asalTerminal.photos", "jadwalKelasBus.jadwal.rute.tujuanTerminal.photos", "jadwalKelasBus.kelasBus", "kursi", "pembayaran"])
            ->where("kode_tiket", $kodeTicket)
            ->first();

        if (!$tiket) {
            Log::info("verify failed: ticket not found", ["kode_tiket" => $kodeTicket, "caller_id" => $callerId]);
            return response()->json(
                [
                    "success" => false,
                    "message" => "Tiket tidak ditemukan.",
                ],
                404,
            );
        }

        // Compute departure datetime and allowed grace (24 hours)
        $jadwal = $tiket->jadwalKelasBus->jadwal ?? null;
        $allowedUntil = null;
        if ($jadwal) {
            try {
                $departureDate = $jadwal->tanggal_berangkat;
                $departureTime = $jadwal->jam_berangkat;
                $departureDatetime = Carbon::parse($departureDate->format("Y-m-d") . " " . $departureTime->format("H:i:s"));
                $graceHours = 24;
                $allowedUntil = $departureDatetime->copy()->addHours($graceHours);
            } catch (\Exception $e) {
                Log::warning("Failed to parse departure datetime for ticket verify", ["kode_tiket" => $tiket->kode_tiket, "error" => $e->getMessage()]);
                $allowedUntil = null;
            }
        }

        // If ticket already marked 'selesai' (used)
        if ($tiket->status === "selesai") {
            if ($isGet) {
                // For GET, no idempotent; ticket is invalid if already used
                Log::info("verify GET rejected: ticket already used", ["kode_tiket" => $kodeTicket, "caller_id" => $callerId]);
                return response()->json(
                    [
                        "success" => false,
                        "message" => "Tiket sudah pernah digunakan.",
                        "data" => [
                            "kode_tiket" => $tiket->kode_tiket,
                            "status" => $tiket->status,
                            "waktu_digunakan" => $tiket->waktu_scan ?? $tiket->updated_at,
                        ],
                    ],
                    409,
                );
            } else {
                // For POST, allow idempotent success within grace period
                if ($allowedUntil && Carbon::now()->lessThanOrEqualTo($allowedUntil)) {
                    Log::info("verify POST idempotent success: ticket already completed but within grace period", ["kode_tiket" => $kodeTicket, "caller_id" => $callerId, "allowed_until" => $allowedUntil->toDateTimeString()]);
                    return response()->json([
                        "success" => true,
                        "message" => "Tiket valid",
                        "data" => [
                            "kode_tiket" => $tiket->kode_tiket,
                            "nama_penumpang" => $tiket->nama_penumpang,
                            "status" => $tiket->status,
                            "waktu_scan" => $tiket->waktu_scan,
                            "penumpang" => [
                                "nama" => $tiket->nama_penumpang,
                                "nik" => $tiket->nik,
                                "jenis_kelamin" => $tiket->jenis_kelamin === "L" ? "Laki-laki" : "Perempuan",
                                "nomor_telepon" => $tiket->nomor_telepon,
                            ],
                            "jadwal" => [
                                "tanggal_berangkat" => $tiket->jadwalKelasBus->jadwal->tanggal_berangkat,
                                "jam_berangkat" => $tiket->jadwalKelasBus->jadwal->jam_berangkat,
                            ],
                            "bus" => [
                                "nama" => $tiket->jadwalKelasBus->jadwal->bus->nama,
                                "plat_nomor" => $tiket->jadwalKelasBus->jadwal->bus->plat_nomor,
                                "foto" => $tiket->jadwalKelasBus->jadwal->bus->photos
                                    ->map(function ($photo) {
                                        return asset("storage/" . $photo->path);
                                    })
                                    ->toArray(),
                            ],
                            "rute" => [
                                "asal" => $tiket->jadwalKelasBus->jadwal->rute->asalTerminal->nama_terminal,
                                "asal_foto" => $tiket->jadwalKelasBus->jadwal->rute->asalTerminal->photos->map(fn($photo) => asset("storage/" . $photo->path)),
                                "tujuan" => $tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->nama_terminal,
                                "tujuan_foto" => $tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->photos->map(fn($photo) => asset("storage/" . $photo->path)),
                            ],
                            "kelas" => $tiket->jadwalKelasBus->kelasBus->nama_kelas,
                            "kursi" => [
                                "nomor" => $tiket->kursi->nomor_kursi,
                                "posisi" => $tiket->kursi->posisi,
                            ],
                            "harga" => $tiket->harga,
                            "pembayaran" => [
                                "metode" => $tiket->pembayaran->metode ?? null,
                                "waktu_bayar" => $tiket->pembayaran->waktu_bayar ?? null,
                            ],
                        ],
                    ]);
                } else {
                    Log::info("verify POST rejected: ticket already used and beyond grace", ["kode_tiket" => $kodeTicket, "caller_id" => $callerId, "allowed_until" => $allowedUntil ? $allowedUntil->toDateTimeString() : null]);
                    return response()->json(
                        [
                            "success" => false,
                            "message" => "Tiket sudah pernah digunakan.",
                            "data" => [
                                "kode_tiket" => $tiket->kode_tiket,
                                "status" => $tiket->status,
                                "waktu_digunakan" => $tiket->waktu_scan ?? $tiket->updated_at,
                            ],
                        ],
                        409,
                    );
                }
            }
        }

        // Ticket is not 'selesai' — require payment
        if ($tiket->status !== "dibayar") {
            Log::info("verify rejected: ticket not paid or cancelled", ["kode_tiket" => $kodeTicket, "caller_id" => $callerId, "ticket_status" => $tiket->status]);
            return response()->json(
                [
                    "success" => false,
                    "message" => "Tiket belum dibayar atau sudah dibatalkan.",
                    "data" => [
                        "kode_tiket" => $tiket->kode_tiket,
                        "status" => $tiket->status,
                    ],
                ],
                403,
            );
        }

        // Check if ticket is still within valid time (departure + 24 hours)
        if ($allowedUntil && Carbon::now()->greaterThan($allowedUntil)) {
            Log::info("verify rejected: ticket expired", ["kode_tiket" => $kodeTicket, "caller_id" => $callerId, "allowed_until" => $allowedUntil->toDateTimeString()]);
            return response()->json(
                [
                    "success" => false,
                    "message" => "Tiket sudah kadaluarsa.",
                    "data" => [
                        "kode_tiket" => $tiket->kode_tiket,
                        "status" => $tiket->status,
                    ],
                ],
                403,
            );
        }

        // For POST, only driver can scan
        if (!$isGet) {
            $callerIsDriver = false;
            if (auth()->check() && isset($tiket->jadwalKelasBus->jadwal->sopir)) {
                $callerIsDriver = auth()->id() === $tiket->jadwalKelasBus->jadwal->sopir->user_id;
            }

            if (!$callerIsDriver) {
                Log::info("verify POST rejected: caller is not assigned driver", ["kode_tiket" => $kodeTicket, "caller_id" => $callerId]);
                return response()->json(
                    [
                        "success" => false,
                        "message" => "Hanya driver yang bisa melakukan scan.",
                    ],
                    403,
                );
            }

            Log::info("verify POST: caller is assigned driver, performing checkin", ["kode_tiket" => $kodeTicket, "caller_id" => $callerId]);
            // Lakukan checkin / tandai hadir
            $tiket->update([
                "status" => "selesai",
                "is_hadir" => true,
                "waktu_scan" => now(),
            ]);

            // Refresh data supaya nilai terbaru tersedia pada response
            $tiket->refresh();

            Log::info("verify POST: checkin success", ["kode_tiket" => $kodeTicket, "caller_id" => $callerId, "is_hadir" => $tiket->is_hadir, "waktu_scan" => $tiket->waktu_scan]);

            return response()->json([
                "success" => true,
                "message" => "Scan berhasil, penumpang hadir dicatat.",
                "data" => [
                    "kode_tiket" => $tiket->kode_tiket,
                    "nama_penumpang" => $tiket->nama_penumpang,
                    "status" => $tiket->status,
                    "waktu_scan" => $tiket->waktu_scan,
                    "penumpang" => [
                        "nama" => $tiket->nama_penumpang,
                        "nik" => $tiket->nik,
                        "jenis_kelamin" => $tiket->jenis_kelamin === "L" ? "Laki-laki" : "Perempuan",
                        "nomor_telepon" => $tiket->nomor_telepon,
                    ],
                    "jadwal" => [
                        "tanggal_berangkat" => $tiket->jadwalKelasBus->jadwal->tanggal_berangkat,
                        "jam_berangkat" => $tiket->jadwalKelasBus->jadwal->jam_berangkat,
                    ],
                    "bus" => [
                        "nama" => $tiket->jadwalKelasBus->jadwal->bus->nama,
                        "plat_nomor" => $tiket->jadwalKelasBus->jadwal->bus->plat_nomor,
                        "foto" => $tiket->jadwalKelasBus->jadwal->bus->photos
                            ->map(function ($photo) {
                                return asset("storage/" . $photo->path);
                            })
                            ->toArray(),
                    ],
                    "rute" => [
                        "asal" => $tiket->jadwalKelasBus->jadwal->rute->asalTerminal->nama_terminal,
                        "asal_foto" => $tiket->jadwalKelasBus->jadwal->rute->asalTerminal->photos->map(fn($photo) => asset("storage/" . $photo->path)),
                        "tujuan" => $tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->nama_terminal,
                        "tujuan_foto" => $tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->photos->map(fn($photo) => asset("storage/" . $photo->path)),
                    ],
                    "kelas" => $tiket->jadwalKelasBus->kelasBus->nama_kelas,
                    "kursi" => [
                        "nomor" => $tiket->kursi->nomor_kursi,
                        "posisi" => $tiket->kursi->posisi,
                    ],
                    "harga" => $tiket->harga,
                    "pembayaran" => [
                        "metode" => $tiket->pembayaran->metode ?? null,
                        "waktu_bayar" => $tiket->pembayaran->waktu_bayar ?? null,
                    ],
                ],
            ]);
        }

        // For GET, return valid ticket info without update
        Log::info("verify GET: returning ticket info only", ["kode_tiket" => $kodeTicket, "caller_id" => $callerId]);
        return response()->json([
            "success" => true,
            "message" => "Tiket valid",
            "data" => [
                "id" => $tiket->id,
                "kode_tiket" => $tiket->kode_tiket,
                "nama_penumpang" => $tiket->nama_penumpang,
                "status" => $tiket->status,
                "waktu_scan" => null,
                "waktu_pesan" => $tiket->waktu_pesan,
                "penumpang" => [
                    "nama" => $tiket->nama_penumpang,
                    "nik" => $tiket->nik,
                    "jenis_kelamin" => $tiket->jenis_kelamin === "L" ? "Laki-laki" : "Perempuan",
                    "nomor_telepon" => $tiket->nomor_telepon,
                    "email" => $tiket->email,
                ],
                "jadwal" => [
                    "tanggal_berangkat" => $tiket->jadwalKelasBus->jadwal->tanggal_berangkat,
                    "jam_berangkat" => $tiket->jadwalKelasBus->jadwal->jam_berangkat,
                ],
                "bus" => [
                    "nama" => $tiket->jadwalKelasBus->jadwal->bus->nama,
                    "plat_nomor" => $tiket->jadwalKelasBus->jadwal->bus->plat_nomor,
                    "foto" => $tiket->jadwalKelasBus->jadwal->bus->photos
                        ->map(function ($photo) {
                            return asset("storage/" . $photo->path);
                        })
                        ->toArray(),
                ],
                "rute" => [
                    "asal" => $tiket->jadwalKelasBus->jadwal->rute->asalTerminal->nama_terminal,
                    "asal_foto" => $tiket->jadwalKelasBus->jadwal->rute->asalTerminal->photos->map(fn($photo) => asset("storage/" . $photo->path)),
                    "tujuan" => $tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->nama_terminal,
                    "tujuan_foto" => $tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->photos->map(fn($photo) => asset("storage/" . $photo->path)),
                ],
                "kelas" => $tiket->jadwalKelasBus->kelasBus->nama_kelas,
                "kursi" => [
                    "nomor" => $tiket->kursi->nomor_kursi,
                    "posisi" => $tiket->kursi->posisi,
                ],
                "harga" => $tiket->harga,
                "pembayaran" => [
                    "metode" => $tiket->pembayaran->metode ?? null,
                    "waktu_bayar" => $tiket->pembayaran->waktu_bayar ?? null,
                ],
            ],
        ]);
    }

    /**
     * Tandai tiket sebagai digunakan (penumpang naik)
     * POST /api/tiket/{kode_tiket}/checkin
     */
    public function checkin($kodeTicket)
    {
        $tiket = Tiket::where("kode_tiket", $kodeTicket)->whereHas("jadwalKelasBus.jadwal.sopir", fn($q) => $q->where("user_id", auth()->id()))->first();

        if (!$tiket) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Tiket tidak ditemukan atau tidak untuk bus Anda.",
                ],
                404,
            );
        }

        if ($tiket->status !== "dibayar") {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Tiket belum dibayar atau tidak valid",
                ],
                403,
            );
        }

        if ($tiket->status === "selesai") {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Tiket sudah pernah digunakan",
                ],
                409,
            );
        }

        $tiket->update([
            "status" => "selesai",
            "is_hadir" => true,
            "waktu_scan" => now(),
        ]);

        return response()->json([
            "success" => true,
            "message" => "Check-in berhasil, penumpang hadir dicatat",
            "data" => [
                "kode_tiket" => $tiket->kode_tiket,
                "nama_penumpang" => $tiket->nama_penumpang,
                "status" => $tiket->status,
                "waktu_checkin" => now(),
            ],
        ]);
    }
}
