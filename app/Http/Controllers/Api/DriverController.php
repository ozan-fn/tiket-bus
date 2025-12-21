<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Tiket;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class DriverController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // Only return schedules that are active (not expired) and assigned to this driver
        $jadwals = Jadwal::whereHas("sopir", fn($q) => $q->where("user_id", auth()->id()))
            ->where("tanggal_berangkat", ">=", now()->toDateString())
            ->with("bus.photos", "rute.asalTerminal.photos", "rute.tujuanTerminal.photos", "jadwalKelasBus.kelasBus")
            ->orderBy("tanggal_berangkat", "desc")
            ->orderBy("jam_berangkat", "desc")
            ->get();

        $jadwals->transform(function ($jadwal) {
            $jadwal->bus->foto = $jadwal->bus->photos->map(fn($photo) => asset("storage/" . $photo->path));
            unset($jadwal->bus->photos);
            $jadwal->rute->asalTerminal->foto = $jadwal->rute->asalTerminal->photos->map(fn($photo) => asset("storage/" . $photo->path));
            unset($jadwal->rute->asalTerminal->photos);
            $jadwal->rute->tujuanTerminal->foto = $jadwal->rute->tujuanTerminal->photos->map(fn($photo) => asset("storage/" . $photo->path));
            unset($jadwal->rute->tujuanTerminal->photos);
            return $jadwal;
        });

        return response()->json([
            "success" => true,
            "data" => $jadwals,
        ]);
    }

    public function kursi($jadwalId): JsonResponse
    {
        // Ensure the driver can only fetch seat data for active (not-expired) schedules assigned to them
        $jadwal = Jadwal::whereHas("sopir", fn($q) => $q->where("user_id", auth()->id()))->with("bus.photos", "jadwalKelasBus.kelasBus.kursi", "rute.asalTerminal.photos", "rute.tujuanTerminal.photos")->findOrFail($jadwalId);

        $kursiData = [];
        foreach ($jadwal->jadwalKelasBus as $jkb) {
            foreach ($jkb->kelasBus->kursi as $kursi) {
                $tiket = Tiket::where("jadwal_kelas_bus_id", $jkb->id)->where("kursi_id", $kursi->id)->with("user")->first();
                $status = "kosong";
                $penumpang = null;
                if ($tiket) {
                    if ($tiket->is_hadir) {
                        $status = "hadir";
                    } elseif ($tiket->status === "dibayar") {
                        $status = "terisi";
                    } else {
                        $status = "batal";
                    }
                    $penumpang = [
                        "nama" => $tiket->nama_penumpang,
                        "nik" => $tiket->nik,
                        "jenis_kelamin" => $tiket->jenis_kelamin,
                        "nomor_telepon" => $tiket->nomor_telepon,
                        "email" => $tiket->email,
                    ];
                }
                $kursiData[] = [
                    "id" => $kursi->id,
                    "nomor_kursi" => $kursi->nomor_kursi,
                    "kelas" => $jkb->kelasBus->nama_kelas,
                    "status" => $status,
                    "penumpang" => $penumpang,
                ];
            }
        }

        $jadwal->bus->foto = $jadwal->bus->photos->map(fn($photo) => asset("storage/" . $photo->path));
        unset($jadwal->bus->photos);
        $jadwal->rute->asalTerminal->foto = $jadwal->rute->asalTerminal->photos->map(fn($photo) => asset("storage/" . $photo->path));
        unset($jadwal->rute->asalTerminal->photos);
        $jadwal->rute->tujuanTerminal->foto = $jadwal->rute->tujuanTerminal->photos->map(fn($photo) => asset("storage/" . $photo->path));
        unset($jadwal->rute->tujuanTerminal->photos);

        return response()->json([
            "success" => true,
            "data" => [
                "jadwal" => $jadwal,
                "kursi" => $kursiData,
            ],
        ]);
    }

    public function verify(Request $request, $kodeTicket): JsonResponse
    {
        // Only allow verification if the ticket belongs to a schedule assigned to this authenticated driver
        // and that schedule is still active (not expired).
        // Ambil tiket jika jadwalnya milik sopir pemanggil (boleh expired)
        $tiket = Tiket::with(["jadwalKelasBus.jadwal.bus.photos", "jadwalKelasBus.jadwal.rute.asalTerminal.photos", "jadwalKelasBus.jadwal.rute.tujuanTerminal.photos", "jadwalKelasBus.kelasBus", "kursi", "pembayaran"])
            ->where("kode_tiket", $kodeTicket)
            ->whereHas("jadwalKelasBus.jadwal.sopir", fn($q) => $q->where("user_id", auth()->id()))
            ->first();

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
                    "message" => "Tiket belum dibayar atau sudah dibatalkan.",
                    "data" => [
                        "kode_tiket" => $tiket->kode_tiket,
                        "status" => $tiket->status,
                    ],
                ],
                403,
            );
        }

        if ($tiket->status === "selesai") {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Tiket sudah pernah digunakan.",
                    "data" => [
                        "kode_tiket" => $tiket->kode_tiket,
                        "status" => $tiket->status,
                        "waktu_digunakan" => $tiket->updated_at,
                    ],
                ],
                409,
            );
        }

        // Periksa masa validasi: izinkan verifikasi hingga 24 jam setelah waktu keberangkatan
        // Jika jadwal tersedia, hitung departure datetime
        $jadwal = $tiket->jadwalKelasBus->jadwal ?? null;
        if ($jadwal) {
            try {
                // tanggal_berangkat and jam_berangkat are stored as datetimes; build a single datetime
                $departureDate = $jadwal->tanggal_berangkat;
                $departureTime = $jadwal->jam_berangkat;
                // Safely compose departure datetime (handle if fields are Carbon instances)
                $departureDatetime = Carbon::parse($departureDate->format("Y-m-d") . " " . $departureTime->format("H:i:s"));
                $graceHours = 24;
                $allowedUntil = $departureDatetime->copy()->addHours($graceHours);

                if (Carbon::now()->greaterThan($allowedUntil)) {
                    return response()->json(
                        [
                            "success" => false,
                            "message" => "Tiket telah melewati masa validasi (grace period {$graceHours} jam).",
                            "data" => [
                                "kode_tiket" => $tiket->kode_tiket,
                                "departure_datetime" => $departureDatetime->toDateTimeString(),
                                "allowed_until" => $allowedUntil->toDateTimeString(),
                                "now" => Carbon::now()->toDateTimeString(),
                            ],
                        ],
                        410,
                    );
                }
            } catch (\Exception $e) {
                // Jika terjadi kesalahan dalam parsing waktu, log dan lanjutkan (lebih aman untuk menolak)
                \Log::warning("Failed to parse departure datetime for ticket verify", ["kode_tiket" => $tiket->kode_tiket, "error" => $e->getMessage()]);
                return response()->json(
                    [
                        "success" => false,
                        "message" => "Tidak dapat memverifikasi tiket karena kesalahan data jadwal.",
                    ],
                    500,
                );
            }
        }

        // Lakukan checkin
        $tiket->update([
            "status" => "selesai",
            "is_hadir" => true,
            "waktu_scan" => now(),
        ]);

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
                    "foto" => $tiket->jadwalKelasBus->jadwal->bus->photos->map(fn($photo) => asset("storage/" . $photo->path)),
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
}
