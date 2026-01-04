<?php

namespace App\Http\Controllers;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

use App\Models\Jadwal;
use App\Models\Tiket;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PemesananController extends Controller
{
    public function index(Request $request): View
    {
        $jadwals = Jadwal::with(["bus.fasilitas", "sopir.user", "conductor.user", "rute.asalTerminal", "rute.tujuanTerminal", "jadwalKelasBus.kelasBus"])
            ->active()
            ->has("jadwalKelasBus")
            ->when($request->asal, fn($q) => $q->whereHas("rute.asalTerminal", fn($q2) => $q2->where("nama_terminal", "like", "%" . $request->asal . "%")->orWhere("nama_kota", "like", "%" . $request->asal . "%")))
            ->when($request->tujuan, fn($q) => $q->whereHas("rute.tujuanTerminal", fn($q2) => $q2->where("nama_terminal", "like", "%" . $request->tujuan . "%")->orWhere("nama_kota", "like", "%" . $request->tujuan . "%")))
            ->when($request->tanggal, fn($q) => $q->whereDate("tanggal_berangkat", $request->tanggal))
            ->orderBy("tanggal_berangkat", "asc")
            ->orderBy("jam_berangkat", "asc")
            ->paginate(12);

        return view("pemesanan.index", compact("jadwals"));
    }

    public function create(Jadwal $jadwal): View
    {
        $jadwal->load([
            "bus",
            "sopir.user",
            "conductor.user",
            "rute.asalTerminal",
            "rute.tujuanTerminal",
            "jadwalKelasBus.kelasBus",
            "jadwalKelasBus.busKelasBus.kursi"
        ]);

        $seatsData = $jadwal->jadwalKelasBus->keyBy('id')->map(function ($jkb) {
            return [
                'class' => $jkb->kelasBus->nama_kelas,
                'harga' => $jkb->harga,
                'kursis' => $jkb->busKelasBus ? $jkb->busKelasBus->kursi->map(fn($k) => [
                    'id' => $k->id,
                    'nomor' => $k->nomor_kursi
                ])->values() : []
            ];
        });

        // Get booked seat IDs for this jadwal_kelas_bus
        $bookedSeatIds = Tiket::whereIn(
            "jadwal_kelas_bus_id",
            $jadwal->jadwalKelasBus->pluck("id")
        )->pluck("kursi_id")->toArray();

        // Load current user data
        $user = auth()->user();

        return view("pemesanan.create", compact("jadwal", "bookedSeatIds", "user", "seatsData"));
    }

    public function store(Request $request, Jadwal $jadwal): RedirectResponse
    {
        $request->validate([
            "jadwal_kelas_bus_id" => "required|exists:jadwal_kelas_bus,id",
            "kursi_id" => "required|exists:kursi,id",
            "nama_penumpang" => "required|string|max:255",
            "nik" => "required|string|max:20",
            "jenis_kelamin" => "required|in:L,P",
            "tanggal_lahir" => "required|date|before:today",
            "nomor_telepon" => "required|string|max:20",
            "email" => "required|email",
        ]);

        // Validate kursi belongs to selected jadwal_kelas_bus
        $jkb = $jadwal->jadwalKelasBus()->findOrFail($request->jadwal_kelas_bus_id);
        $kursi = $jkb->busKelasBus->kursi()->findOrFail($request->kursi_id);

        // Check if seat already booked (status dipesan atau dibayar)
        $existing = Tiket::where("jadwal_kelas_bus_id", $jkb->id)
            ->where("kursi_id", $request->kursi_id)
            ->whereIn("status", ["dipesan", "dibayar"])
            ->exists();
        if ($existing) {
            return back()->withErrors(["kursi_id" => "Kursi sudah dipesan"]);
        }

        // Generate unique kode_tiket
        $kode_tiket = $this->generateKodeTiket();

        // Create tiket with status 'dipesan' (pending)
        $tiket = Tiket::create([
            "jadwal_kelas_bus_id" => $request->jadwal_kelas_bus_id,
            "kursi_id" => $request->kursi_id,
            "user_id" => auth()->id(),
            "nama_penumpang" => $request->nama_penumpang,
            "nik" => $request->nik,
            "jenis_kelamin" => $request->jenis_kelamin,
            "tanggal_lahir" => $request->tanggal_lahir,
            "nomor_telepon" => $request->nomor_telepon,
            "email" => $request->email,
            "kode_tiket" => $kode_tiket,
            "harga" => $jkb->harga,
            "status" => "dipesan", // Pending status, menunggu pembayaran
        ]);

        // Redirect ke halaman pembayaran
        return redirect()->route("pemesanan.pembayaran", $tiket->id)
            ->with("success", "Tiket berhasil dipesan. Silakan lakukan pembayaran.");
    }

    private function generateKodeTiket(): string
    {
        do {
            $kode = 'TKT' . strtoupper(uniqid());
        } while (Tiket::where('kode_tiket', $kode)->exists());

        return $kode;
    }

    public function pembayaran(Tiket $tiket)
    {
        // Otorisasi: hanya pemilik tiket yang bisa lihat halaman pembayaran
        if ($tiket->user_id !== auth()->id()) {
            abort(403, "Anda tidak berhak melihat halaman ini");
        }

        // Cek status tiket: hanya bisa bayar jika masih 'dipesan'
        if ($tiket->status !== "dipesan") {
            return redirect()->route("tiket.index")
                ->withErrors(["error" => "Tiket tidak bisa dibayar lagi"]);
        }

        // Load relasi yang dibutuhkan
        $tiket->load("jadwalKelasBus.jadwal.rute", "jadwalKelasBus.kelasBus", "kursi");

        return view("pemesanan.pembayaran", compact("tiket"));
    }

    public function pembayaranStore(Request $request, Tiket $tiket): RedirectResponse
    {
        // Otorisasi
        if ($tiket->user_id !== auth()->id()) {
            abort(403, "Anda tidak berhak melakukan aksi ini");
        }

        // Validasi
        $request->validate([
            "metode" => "required|in:xendit,transfer,tunai",
        ]);

        // Cek status tiket
        if ($tiket->status !== "dipesan") {
            return back()->withErrors(["error" => "Tiket sudah tidak bisa dibayar"]);
        }

        // Generate kode transaksi
        $kode_transaksi = $this->generateKodeTransaksi();

        // Buat pembayaran dengan status 'dipesan' (pending)
        $pembayaran = \App\Models\Pembayaran::create([
            "user_id" => auth()->id(),
            "tiket_id" => $tiket->id,
            "metode" => $request->metode,
            "nominal" => $tiket->harga,
            "status" => "dipesan",
            "kode_transaksi" => $kode_transaksi,
        ]);

        // Handle sesuai metode pembayaran
        if ($request->metode === "xendit") {
            // Integrasi Xendit untuk pembayaran online
            $xenditResponse = \Illuminate\Support\Facades\Http::withHeaders([
                "Authorization" => "Basic " . base64_encode(config("services.xendit.api_key") . ":"),
                "Content-Type" => "application/json",
                "xendit-api-version" => "2022-07-31",
            ])->post(config("services.xendit.base_url") . "/v2/invoices", [
                        "external_id" => $kode_transaksi,
                        "amount" => $tiket->harga,
                        "currency" => "IDR",
                        "description" => "Pembayaran Tiket Bus - " . $tiket->kode_tiket,
                        "invoice_duration" => 86400, // 24 hours
                        "customer" => [
                            "given_names" => $tiket->nama_penumpang,
                            "email" => $tiket->email,
                            "mobile_number" => $tiket->nomor_telepon,
                        ],
                        "customer_notification_preference" => [
                            "invoice_created" => ["email"],
                            "invoice_reminder" => ["email"],
                            "invoice_paid" => ["email"],
                        ],
                        "success_redirect_url" => route("tiket.index"),
                        "failure_redirect_url" => route("pemesanan.pembayaran", $tiket->id),
                        "payment_methods" => ["CREDIT_CARD", "BANK_TRANSFER", "EWALLET", "QR_CODE", "RETAIL_OUTLET"],
                        "items" => [
                            [
                                "name" => "Tiket Bus - " . $tiket->kode_tiket,
                                "quantity" => 1,
                                "price" => $tiket->harga,
                                "category" => "Transportation",
                            ],
                        ],
                    ]);

            if ($xenditResponse->failed()) {
                $pembayaran->update(["status" => "gagal"]);
                return back()->withErrors(["error" => "Gagal membuat invoice Xendit. Silakan coba lagi."]);
            }

            $invoiceData = $xenditResponse->json();
            $pembayaran->update(["external_id" => $invoiceData["id"] ?? null]);

            // Redirect ke invoice Xendit
            return redirect()->to($invoiceData["invoice_url"] ?? route("pemesanan.pembayaran", $tiket->id))
                ->with("info", "Silakan lakukan pembayaran melalui Xendit");
        } elseif ($request->metode === "transfer") {
            // Redirect ke halaman instruksi transfer
            return redirect()->route("pembayaran.instruksi", $pembayaran->id)
                ->with("success", "Silakan lakukan transfer sesuai instruksi yang ditampilkan");
        } else { // tunai
            // Redirect ke halaman konfirmasi tunai
            return redirect()->route("pembayaran.tunai", $pembayaran->id)
                ->with("success", "Tiket Anda siap. Silakan bayar saat naik di terminal.");
        }
    }

    private function generateKodeTransaksi(): string
    {
        do {
            $kode = 'TRX' . strtoupper(uniqid());
        } while (\App\Models\Pembayaran::where('kode_transaksi', $kode)->exists());

        return $kode;
    }

    public function pembayaranInstruksi(\App\Models\Pembayaran $pembayaran): View
    {
        // Otorisasi
        if ($pembayaran->user_id !== auth()->id()) {
            abort(403, "Anda tidak berhak melihat halaman ini");
        }

        $pembayaran->load("tiket.jadwalKelasBus.jadwal.rute");

        return view("pembayaran.instruksi", compact("pembayaran"));
    }

    public function pembayaranTunai(\App\Models\Pembayaran $pembayaran): View
    {
        // Otorisasi
        if ($pembayaran->user_id !== auth()->id()) {
            abort(403, "Anda tidak berhak melihat halaman ini");
        }

        $pembayaran->load("tiket.jadwalKelasBus.jadwal.rute");

        return view("pembayaran.tunai", compact("pembayaran"));
    }

    public function show(Tiket $tiket): View
    {
        // Otorisasi sederhana: hanya pemilik atau admin/super_admin
        $user = auth()->user();
        if ($tiket->user_id !== $user?->id && !$user?->hasRole("admin") && !$user?->hasRole("super_admin")) {
            abort(403, "Anda tidak berhak melihat tiket ini");
        }

        // Eager load fallback: jika kolom jadwal_id kosong gunakan jalur jadwalKelasBus
        $tiket->load(["jadwal.bus", "jadwal.sopir.user", "jadwal.rute.asalTerminal", "jadwal.rute.tujuanTerminal", "jadwalKelasBus.jadwal.bus", "jadwalKelasBus.jadwal.sopir.user", "jadwalKelasBus.jadwal.rute.asalTerminal", "jadwalKelasBus.jadwal.rute.tujuanTerminal", "kursi"]);
        return view("pemesanan.show", compact("tiket"));
    }

    public function adminIndex(Request $request): View
    {
        $jadwals = Jadwal::with("bus", "sopir.user", "conductor.user", "rute.asalTerminal", "rute.tujuanTerminal", "jadwalKelasBus")
            ->where("status", "aktif")
            ->whereDate("tanggal_berangkat", ">=", now()->toDateString())
            ->when($request->asal, fn($q) => $q->whereHas("rute.asalTerminal", fn($q2) => $q2->where("nama_terminal", "like", "%" . $request->asal . "%")))
            ->when($request->tujuan, fn($q) => $q->whereHas("rute.tujuanTerminal", fn($q2) => $q2->where("nama_terminal", "like", "%" . $request->tujuan . "%")))
            ->when($request->tanggal, fn($q) => $q->whereDate("tanggal_berangkat", $request->tanggal))
            ->orderBy("tanggal_berangkat", "asc")
            ->orderBy("jam_berangkat", "asc")
            ->paginate(15);

        return view("admin.pemesanan.index", compact("jadwals"));
    }

    public function adminCreate(Jadwal $jadwal): View
    {
        $jadwal->load("bus", "sopir.user", "conductor.user", "rute.asalTerminal", "rute.tujuanTerminal", "jadwalKelasBus.kelasBus");
        $kursiTerpakai = Tiket::whereHas("jadwalKelasBus", fn($q) => $q->where("jadwal_id", $jadwal->id))->pluck("kursi_id")->toArray();
        return view("admin.pemesanan.create", compact("jadwal", "kursiTerpakai"));
    }

    public function adminStore(Request $request, Jadwal $jadwal): RedirectResponse
    {
        $request->validate([
            "nama_penumpang" => "required|string|max:255",
            "nik" => "required|string|max:20",
            "jenis_kelamin" => "required|in:L,P",
            "tanggal_lahir" => "required|date",
            "nomor_telepon" => "required|string|max:20",
            "email" => "required|email",
            "jadwal_kelas_bus_id" => "required|exists:jadwal_kelas_bus,id",
            "kursi_id" => "required|exists:kursi,id",
        ]);

        // Cek kursi sudah dipesan atau belum
        $kursiTerpakai = Tiket::where("jadwal_kelas_bus_id", $request->jadwal_kelas_bus_id)->where("kursi_id", $request->kursi_id)->exists();

        if ($kursiTerpakai) {
            return back()->withErrors(["kursi_id" => "Kursi sudah dipesan"]);
        }

        $kodeTicket = "TKT" . strtoupper(uniqid());
        $jadwalKelasBus = \App\Models\JadwalKelasBus::find($request->jadwal_kelas_bus_id);

        $tiket = Tiket::create([
            "user_id" => auth()->id(),
            "jadwal_kelas_bus_id" => $request->jadwal_kelas_bus_id,
            "kursi_id" => $request->kursi_id,
            "nik" => $request->nik,
            "nama_penumpang" => $request->nama_penumpang,
            "tanggal_lahir" => $request->tanggal_lahir,
            "jenis_kelamin" => $request->jenis_kelamin,
            "nomor_telepon" => $request->nomor_telepon,
            "email" => $request->email,
            "kode_tiket" => $kodeTicket,
            "harga" => $jadwalKelasBus->harga,
            "status" => "dipesan",
            "waktu_pesan" => now(),
        ]);

        // Buat pembayaran dengan status berhasil
        \App\Models\Pembayaran::create([
            "user_id" => auth()->id(),
            "tiket_id" => $tiket->id,
            "metode" => "tunai",
            "nominal" => $tiket->harga,
            "status" => "berhasil",
            "waktu_bayar" => now(),
            "kode_transaksi" => "ADM-" . strtoupper(uniqid()),
        ]);

        // Update status tiket ke dibayar
        $tiket->update(["status" => "dibayar"]);

        return redirect()->route("admin/pemesanan.show", $tiket)->with("success", "Tiket berhasil dipesan dan pembayaran dikonfirmasi.");
    }

    public function adminShow(Tiket $tiket): View
    {
        $tiket->load("jadwalKelasBus.jadwal.bus", "jadwalKelasBus.jadwal.sopir.user", "jadwalKelasBus.jadwal.rute.asalTerminal", "jadwalKelasBus.jadwal.rute.tujuanTerminal", "jadwalKelasBus.kelasBus", "kursi", "user", "pembayaran");

        $qrCode = QrCode::create($tiket->kode_tiket);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $qrCodeDataUri = $result->getDataUri();

        return view("admin.pemesanan.show", compact("tiket", "qrCodeDataUri"));
    }

    public function history(Request $request): View
    {
        $tikets = Tiket::with(["jadwalKelasBus.jadwal.bus", "jadwalKelasBus.jadwal.sopir.user", "jadwalKelasBus.jadwal.rute.asalTerminal", "jadwalKelasBus.jadwal.rute.tujuanTerminal", "jadwalKelasBus.kelasBus", "kursi", "user"])
            ->whereHas("jadwalKelasBus.jadwal") // Pastikan ada jadwal
            ->when($request->status, fn($q) => $q->where("status", $request->status))
            ->when($request->tanggal, fn($q) => $q->whereDate("waktu_pesan", $request->tanggal))
            ->when($request->nama, fn($q) => $q->where("nama_penumpang", "like", "%" . $request->nama . "%"))
            ->when($request->kode_tiket, fn($q) => $q->where("kode_tiket", "like", "%" . $request->kode_tiket . "%"))
            ->orderBy("waktu_pesan", "desc")
            ->paginate(20);

        return view("admin.history-pemesanan", compact("tikets"));
    }
}
