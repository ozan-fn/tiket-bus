<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Role:
        // owner: Mengelola seluruh sistem, termasuk admin dan pengaturan global
        // agent: Mengelola penjualan tiket, harga tiket, dan history pemesanan
        // conductor: Kondektur bus, mengecek penumpang dan data penumpang di bus
        // driver: Sopir bus, bisa login jika diperlukan untuk absen, cek jadwal, atau laporan
        // passenger: Melakukan pemesanan tiket, melihat jadwal, dan riwayat transaksi
        // Membuat role dan permission

        Role::firstOrCreate(["name" => "owner"]);
        Role::firstOrCreate(["name" => "agent"]);
        Role::firstOrCreate(["name" => "conductor"]);
        Role::firstOrCreate(["name" => "driver"]);
        Role::firstOrCreate(["name" => "passenger"]);

        $owner = User::factory()->create([
            "name" => "Owner User",
            "email" => "owner@example.com",
        ]);
        $owner->assignRole("owner");

        $agent = User::factory()->create([
            "name" => "Agent User",
            "email" => "agent@example.com",
        ]);
        $agent->assignRole("agent");

        $conductor = User::factory()->create([
            "name" => "Conductor User",
            "email" => "conductor@example.com",
        ]);
        $conductor->assignRole("conductor");

        $passenger = User::factory()->create([
            "name" => "Passenger User",
            "email" => "passenger@example.com",
        ]);
        $passenger->assignRole("passenger");

        $user = User::factory()->create([
            "name" => "Test User",
            "email" => "user@example.com",
        ]);
        $user->assignRole("passenger");

        $this->command->info("hehe");
        return;

        // Fasilitas
        $ac = \App\Models\Fasilitas::create(["nama" => "AC", "icon" => "snowflake"]);
        $wifi = \App\Models\Fasilitas::create(["nama" => "WiFi", "icon" => "wifi"]);
        $toilet = \App\Models\Fasilitas::create(["nama" => "Toilet", "icon" => "toilet"]);
        $tv = \App\Models\Fasilitas::create(["nama" => "TV", "icon" => "tv"]);
        $snack = \App\Models\Fasilitas::create(["nama" => "Makanan Ringan", "icon" => "utensils"]);
        $usb = \App\Models\Fasilitas::create(["nama" => "Pengisian USB", "icon" => "plug"]);
        $recliner = \App\Models\Fasilitas::create(["nama" => "Kursi Recliner", "icon" => "chair"]);
        $entertainment = \App\Models\Fasilitas::create(["nama" => "Sistem Hiburan", "icon" => "gamepad"]);
        $readingLight = \App\Models\Fasilitas::create(["nama" => "Lampu Baca", "icon" => "lightbulb"]);
        $blanket = \App\Models\Fasilitas::create(["nama" => "Selimut", "icon" => "bed"]);
        $pillow = \App\Models\Fasilitas::create(["nama" => "Bantal", "icon" => "couch"]);
        $water = \App\Models\Fasilitas::create(["nama" => "Air Mineral", "icon" => "bottle-water"]);
        $luggage = \App\Models\Fasilitas::create(["nama" => "Penyimpanan Bagasi", "icon" => "suitcase"]);
        $firstAid = \App\Models\Fasilitas::create(["nama" => "Kotak P3K", "icon" => "medkit"]);
        $gps = \App\Models\Fasilitas::create(["nama" => "Pelacakan GPS", "icon" => "map-marker"]);
        $cctv = \App\Models\Fasilitas::create(["nama" => "CCTV", "icon" => "video-camera"]);
        $powerOutlet = \App\Models\Fasilitas::create(["nama" => "Stopkontak", "icon" => "bolt"]);
        $legroom = \App\Models\Fasilitas::create(["nama" => "Ruang Kaki Tambahan", "icon" => "arrows-alt"]);
        $adjustableSeat = \App\Models\Fasilitas::create(["nama" => "Kursi yang Dapat Disesuaikan", "icon" => "sliders"]);
        $climateControl = \App\Models\Fasilitas::create(["nama" => "Kontrol Iklim", "icon" => "thermometer"]);
        $onboardKitchen = \App\Models\Fasilitas::create(["nama" => "Dapur di Dalam Bus", "icon" => "kitchen-set"]);
        $shower = \App\Models\Fasilitas::create(["nama" => "Kamar Mandi dengan Shower", "icon" => "shower"]);
        $massage = \App\Models\Fasilitas::create(["nama" => "Kursi Pijat", "icon" => "hand-sparkles"]);
        $audio = \App\Models\Fasilitas::create(["nama" => "Sistem Audio", "icon" => "volume-up"]);
        $coffee = \App\Models\Fasilitas::create(["nama" => "Layanan Kopi/Teh", "icon" => "coffee"]);
        $newspaper = \App\Models\Fasilitas::create(["nama" => "Koran/Majalah", "icon" => "newspaper"]);

        // Bus
        $bus1 = \App\Models\Bus::create([
            "nama" => "Bus Mawar",
            "plat_nomor" => "B 1234 MAW",
            "kapasitas" => 40,
            "status" => "aktif",
        ]);
        $bus1->fasilitas()->attach([$ac->id, $wifi->id, $toilet->id, $tv->id, $snack->id, $usb->id, $recliner->id, $entertainment->id, $readingLight->id, $blanket->id, $pillow->id, $water->id, $luggage->id, $firstAid->id, $gps->id, $cctv->id, $powerOutlet->id, $legroom->id, $adjustableSeat->id, $climateControl->id, $onboardKitchen->id, $shower->id, $massage->id, $audio->id, $coffee->id, $newspaper->id]);

        $bus2 = \App\Models\Bus::create([
            "nama" => "Bus Melati",
            "plat_nomor" => "B 5678 MEL",
            "kapasitas" => 32,
            "status" => "aktif",
        ]);
        $bus2->fasilitas()->attach([$ac->id, $toilet->id, $wifi->id, $tv->id, $snack->id, $usb->id, $recliner->id, $entertainment->id, $readingLight->id, $blanket->id, $pillow->id, $water->id, $luggage->id, $firstAid->id, $gps->id, $cctv->id, $powerOutlet->id, $legroom->id, $adjustableSeat->id, $climateControl->id, $onboardKitchen->id, $shower->id, $massage->id, $audio->id, $coffee->id, $newspaper->id]);

        $bus3 = \App\Models\Bus::create([
            "nama" => "Bus Anggrek",
            "plat_nomor" => "B 9012 ANG",
            "kapasitas" => 35,
            "status" => "aktif",
        ]);
        $bus3->fasilitas()->attach([$ac->id, $tv->id, $wifi->id, $toilet->id, $snack->id, $usb->id, $recliner->id, $entertainment->id, $readingLight->id, $blanket->id, $pillow->id, $water->id, $luggage->id, $firstAid->id, $gps->id, $cctv->id, $powerOutlet->id, $legroom->id, $adjustableSeat->id, $climateControl->id, $onboardKitchen->id, $shower->id, $massage->id, $audio->id, $coffee->id, $newspaper->id]);

        $bus4 = \App\Models\Bus::create([
            "nama" => "Bus Tulip",
            "plat_nomor" => "B 3456 TUL",
            "kapasitas" => 38,
            "status" => "aktif",
        ]);
        $bus4->fasilitas()->attach([$wifi->id, $snack->id, $ac->id, $toilet->id, $tv->id, $usb->id, $recliner->id, $entertainment->id, $readingLight->id, $blanket->id, $pillow->id, $water->id, $luggage->id, $firstAid->id, $gps->id, $cctv->id, $powerOutlet->id, $legroom->id, $adjustableSeat->id, $climateControl->id, $onboardKitchen->id, $shower->id, $massage->id, $audio->id, $coffee->id, $newspaper->id]);

        $bus5 = \App\Models\Bus::create([
            "nama" => "Bus Sakura",
            "plat_nomor" => "B 7890 SAK",
            "kapasitas" => 30,
            "status" => "aktif",
        ]);
        $bus5->fasilitas()->attach([$toilet->id, $usb->id, $ac->id, $wifi->id, $tv->id, $snack->id, $recliner->id, $entertainment->id, $readingLight->id, $blanket->id, $pillow->id, $water->id, $luggage->id, $firstAid->id, $gps->id, $cctv->id, $powerOutlet->id, $legroom->id, $adjustableSeat->id, $climateControl->id, $onboardKitchen->id, $shower->id, $massage->id, $audio->id, $coffee->id, $newspaper->id]);

        // Kelas Bus
        $kelasBus1Ekonomi = \App\Models\KelasBus::create([
            "bus_id" => $bus1->id,
            "nama_kelas" => "Ekonomi",
            "deskripsi" => "Kelas ekonomi di lantai bawah dengan kursi standar",
            "jumlah_kursi" => 20,
        ]);

        $kelasBus1Bisnis = \App\Models\KelasBus::create([
            "bus_id" => $bus1->id,
            "nama_kelas" => "Bisnis",
            "deskripsi" => "Kelas bisnis di lantai atas dengan kursi lebih nyaman",
            "jumlah_kursi" => 20,
        ]);

        $kelasBus2Ekonomi = \App\Models\KelasBus::create([
            "bus_id" => $bus2->id,
            "nama_kelas" => "Ekonomi",
            "deskripsi" => "Kelas ekonomi di lantai bawah dengan kursi standar",
            "jumlah_kursi" => 16,
        ]);

        $kelasBus2Bisnis = \App\Models\KelasBus::create([
            "bus_id" => $bus2->id,
            "nama_kelas" => "Bisnis",
            "deskripsi" => "Kelas bisnis di lantai atas dengan kursi lebih nyaman",
            "jumlah_kursi" => 16,
        ]);

        $kelasBus3Ekonomi = \App\Models\KelasBus::create([
            "bus_id" => $bus3->id,
            "nama_kelas" => "Ekonomi",
            "deskripsi" => "Kelas ekonomi di lantai bawah dengan kursi standar",
            "jumlah_kursi" => 18,
        ]);

        $kelasBus3VIP = \App\Models\KelasBus::create([
            "bus_id" => $bus3->id,
            "nama_kelas" => "VIP",
            "deskripsi" => "Kelas VIP di lantai atas dengan kursi premium dan fasilitas eksklusif",
            "jumlah_kursi" => 17,
        ]);

        $kelasBus4Ekonomi = \App\Models\KelasBus::create([
            "bus_id" => $bus4->id,
            "nama_kelas" => "Ekonomi",
            "deskripsi" => "Kelas ekonomi di lantai bawah dengan kursi standar",
            "jumlah_kursi" => 19,
        ]);

        $kelasBus4Bisnis = \App\Models\KelasBus::create([
            "bus_id" => $bus4->id,
            "nama_kelas" => "Bisnis",
            "deskripsi" => "Kelas bisnis di lantai atas dengan kursi lebih nyaman",
            "jumlah_kursi" => 19,
        ]);

        $kelasBus5Ekonomi = \App\Models\KelasBus::create([
            "bus_id" => $bus5->id,
            "nama_kelas" => "Ekonomi",
            "deskripsi" => "Kelas ekonomi di lantai bawah dengan kursi standar",
            "jumlah_kursi" => 15,
        ]);

        $kelasBus5VIP = \App\Models\KelasBus::create([
            "bus_id" => $bus5->id,
            "nama_kelas" => "VIP",
            "deskripsi" => "Kelas VIP di lantai atas dengan kursi premium dan fasilitas eksklusif",
            "jumlah_kursi" => 15,
        ]);

        // Kursi untuk setiap kelas bus
        $kelasArray = [$kelasBus1Ekonomi, $kelasBus1Bisnis, $kelasBus2Ekonomi, $kelasBus2Bisnis, $kelasBus3Ekonomi, $kelasBus3VIP, $kelasBus4Ekonomi, $kelasBus4Bisnis, $kelasBus5Ekonomi, $kelasBus5VIP];

        foreach ($kelasArray as $kelas) {
            for ($i = 1; $i <= $kelas->jumlah_kursi; $i++) {
                \App\Models\Kursi::create([
                    "kelas_bus_id" => $kelas->id,
                    "nomor_kursi" => $i,
                    "index" => $i - 1,
                ]);
            }
        }

        // Terminal
        $terminalJakarta = \App\Models\Terminal::create(["nama_terminal" => "Terminal Pulo Gebang", "nama_kota" => "Jakarta", "alamat" => "Jl. Pulo Gebang"]);
        $terminalSurabaya = \App\Models\Terminal::create(["nama_terminal" => "Terminal Bungurasih", "nama_kota" => "Surabaya", "alamat" => "Jl. Raya Waru"]);
        $terminalCirebon = \App\Models\Terminal::create(["nama_terminal" => "Terminal Harjamukti", "nama_kota" => "Cirebon", "alamat" => "Jl. Harjamukti"]);
        $terminalBandung = \App\Models\Terminal::create(["nama_terminal" => "Terminal Leuwi Panjang", "nama_kota" => "Bandung", "alamat" => "Jl. Soekarno-Hatta"]);
        $terminalSemarang = \App\Models\Terminal::create(["nama_terminal" => "Terminal Terboyo", "nama_kota" => "Semarang", "alamat" => "Jl. Terboyo"]);
        $terminalYogyakarta = \App\Models\Terminal::create(["nama_terminal" => "Terminal Giwangan", "nama_kota" => "Yogyakarta", "alamat" => "Jl. Imogiri Timur"]);
        $terminalMalang = \App\Models\Terminal::create(["nama_terminal" => "Terminal Arjosari", "nama_kota" => "Malang", "alamat" => "Jl. Raden Intan"]);
        $terminalDenpasar = \App\Models\Terminal::create(["nama_terminal" => "Terminal Ubung", "nama_kota" => "Denpasar", "alamat" => "Jl. Cokroaminoto"]);
        $terminalMedan = \App\Models\Terminal::create(["nama_terminal" => "Terminal Amplas", "nama_kota" => "Medan", "alamat" => "Jl. Sisingamangaraja"]);
        $terminalPalembang = \App\Models\Terminal::create(["nama_terminal" => "Terminal Karya Jaya", "nama_kota" => "Palembang", "alamat" => "Jl. Kol. H. Burlian"]);
        $terminalMakassar = \App\Models\Terminal::create(["nama_terminal" => "Terminal Daya", "nama_kota" => "Makassar", "alamat" => "Jl. Perintis Kemerdekaan"]);

        // Rute
        $rute1 = \App\Models\Rute::create(["asal_terminal_id" => $terminalJakarta->id, "tujuan_terminal_id" => $terminalSurabaya->id]);
        $rute1->terminal()->attach($terminalCirebon->id, ["urutan" => 1]);

        $rute2 = \App\Models\Rute::create(["asal_terminal_id" => $terminalBandung->id, "tujuan_terminal_id" => $terminalYogyakarta->id]);
        $rute2->terminal()->attach($terminalSemarang->id, ["urutan" => 1]);

        $rute3 = \App\Models\Rute::create(["asal_terminal_id" => $terminalSurabaya->id, "tujuan_terminal_id" => $terminalDenpasar->id]);
        $rute3->terminal()->attach($terminalMalang->id, ["urutan" => 1]);

        $rute4 = \App\Models\Rute::create(["asal_terminal_id" => $terminalMedan->id, "tujuan_terminal_id" => $terminalPalembang->id]);
        $rute4->terminal()->attach($terminalJakarta->id, ["urutan" => 1]);

        $rute5 = \App\Models\Rute::create(["asal_terminal_id" => $terminalMakassar->id, "tujuan_terminal_id" => $terminalDenpasar->id]);
        $rute5->terminal()->attach($terminalSurabaya->id, ["urutan" => 1]);

        // Rute tambahan
        $rute6 = \App\Models\Rute::create(["asal_terminal_id" => $terminalBandung->id, "tujuan_terminal_id" => $terminalSurabaya->id]);
        $rute6->terminal()->attach($terminalCirebon->id, ["urutan" => 1]);

        $rute7 = \App\Models\Rute::create(["asal_terminal_id" => $terminalJakarta->id, "tujuan_terminal_id" => $terminalYogyakarta->id]);
        $rute7->terminal()->attach($terminalSemarang->id, ["urutan" => 1]);

        $rute8 = \App\Models\Rute::create(["asal_terminal_id" => $terminalMedan->id, "tujuan_terminal_id" => $terminalMakassar->id]);
        $rute8->terminal()->attach($terminalPalembang->id, ["urutan" => 1]);

        $rute9 = \App\Models\Rute::create(["asal_terminal_id" => $terminalDenpasar->id, "tujuan_terminal_id" => $terminalBandung->id]);
        $rute9->terminal()->attach($terminalSurabaya->id, ["urutan" => 1]);

        $rute10 = \App\Models\Rute::create(["asal_terminal_id" => $terminalMakassar->id, "tujuan_terminal_id" => $terminalJakarta->id]);
        $rute10->terminal()->attach($terminalSurabaya->id, ["urutan" => 1]);

        // Membuat akun sopir untuk setiap bus
        $sopirBus1 = User::factory()->create([
            "name" => "Sopir Bus Mawar",
            "email" => "sopir.mawar@example.com",
        ]);
        $sopirBus1->assignRole("driver");

        $sopirBus2 = User::factory()->create([
            "name" => "Sopir Bus Melati",
            "email" => "sopir.melati@example.com",
        ]);
        $sopirBus2->assignRole("driver");

        $sopirBus3 = User::factory()->create([
            "name" => "Sopir Bus Anggrek",
            "email" => "sopir.anggrek@example.com",
        ]);
        $sopirBus3->assignRole("driver");

        $sopirBus4 = User::factory()->create([
            "name" => "Sopir Bus Tulip",
            "email" => "sopir.tulip@example.com",
        ]);
        $sopirBus4->assignRole("driver");

        $sopirBus5 = User::factory()->create([
            "name" => "Sopir Bus Sakura",
            "email" => "sopir.sakura@example.com",
        ]);
        $sopirBus5->assignRole("driver");

        // Sopir
        $sopir = \App\Models\Sopir::create([
            "user_id" => $sopirBus1->id, // Adjusted to use the correct driver account
            "nik" => "3173123456789012",
            "nomor_sim" => "SIM123456",
            "alamat" => "Jl. Sopir Mawar",
            "telepon" => "081234567890",
            "tanggal_lahir" => "1980-01-01",
            "status" => "aktif",
        ]);

        $sopirBus2 = \App\Models\Sopir::create([
            "user_id" => $sopirBus2->id, // Adjusted to use the correct driver account
            "nik" => "3173123456789013",
            "nomor_sim" => "SIM123457",
            "alamat" => "Jl. Sopir Melati",
            "telepon" => "081234567891",
            "tanggal_lahir" => "1981-02-02",
            "status" => "aktif",
        ]);

        $sopirBus3 = \App\Models\Sopir::create([
            "user_id" => $sopirBus3->id, // Adjusted to use the correct driver account
            "nik" => "3173123456789014",
            "nomor_sim" => "SIM123458",
            "alamat" => "Jl. Sopir Anggrek",
            "telepon" => "081234567892",
            "tanggal_lahir" => "1982-03-03",
            "status" => "aktif",
        ]);

        $sopirBus4 = \App\Models\Sopir::create([
            "user_id" => $sopirBus4->id, // Adjusted to use the correct driver account
            "nik" => "3173123456789015",
            "nomor_sim" => "SIM123459",
            "alamat" => "Jl. Sopir Tulip",
            "telepon" => "081234567893",
            "tanggal_lahir" => "1983-04-04",
            "status" => "aktif",
        ]);

        $sopirBus5 = \App\Models\Sopir::create([
            "user_id" => $sopirBus5->id, // Adjusted to use the correct driver account
            "nik" => "3173123456789016",
            "nomor_sim" => "SIM123460",
            "alamat" => "Jl. Sopir Sakura",
            "telepon" => "081234567894",
            "tanggal_lahir" => "1984-05-05",
            "status" => "aktif",
        ]);

        // Generate jadwal untuk 30 hari ke depan (mulai hari ini)
        $buses = [$bus1, $bus2, $bus3, $bus4, $bus5];
        $sopirs = [$sopir, $sopirBus2, $sopirBus3, $sopirBus4, $sopirBus5];
        $rutes = [$rute1, $rute2, $rute3, $rute4, $rute5, $rute6, $rute7, $rute8, $rute9, $rute10];
        $jamKeberangkatan = ["06:00:00", "08:00:00", "10:00:00", "12:00:00", "14:00:00", "16:00:00", "18:00:00", "20:00:00", "22:00:00"];

        $jadwalCollection = [];

        // Generate jadwal untuk 30 hari ke depan
        for ($day = 0; $day < 30; $day++) {
            $tanggal = now()->addDays($day)->toDateString();

            // Untuk setiap hari, buat beberapa jadwal dengan kombinasi berbeda
            foreach ($buses as $busIndex => $bus) {
                // Setiap bus akan beroperasi 2-3 kali per hari dengan rute yang berbeda
                $jumlahJadwalPerHari = rand(2, 3);

                for ($j = 0; $j < $jumlahJadwalPerHari; $j++) {
                    // Pilih rute secara random
                    $rute = $rutes[array_rand($rutes)];
                    $sopir = $sopirs[$busIndex];
                    $conductorIndex = ($busIndex + 1) % count($sopirs);
                    $conductor = $sopirs[$conductorIndex];
                    $jamBerangkat = $jamKeberangkatan[array_rand($jamKeberangkatan)];

                    // Pastikan tidak ada jadwal duplikat (bus yang sama, jam yang sama, di hari yang sama)
                    $key = $bus->id . "-" . $tanggal . "-" . $jamBerangkat;
                    if (!isset($jadwalCollection[$key])) {
                        $jadwal = \App\Models\Jadwal::create([
                            "bus_id" => $bus->id,
                            "sopir_id" => $sopir->id,
                            "conductor_id" => $conductor->id,
                            "rute_id" => $rute->id,
                            "tanggal_berangkat" => $tanggal,
                            "jam_berangkat" => $jamBerangkat,
                            "status" => "tersedia",
                        ]);

                        $jadwalCollection[$key] = $jadwal;

                        // Buat jadwal kelas bus untuk setiap kelas yang tersedia di bus tersebut
                        $kelasBuses = \App\Models\KelasBus::where("bus_id", $bus->id)->get();

                        foreach ($kelasBuses as $kelasBus) {
                            // Harga berdasarkan nama kelas
                            $harga = 100000; // Default
                            if (stripos($kelasBus->nama_kelas, "ekonomi") !== false) {
                                $harga = rand(100000, 150000);
                            } elseif (stripos($kelasBus->nama_kelas, "bisnis") !== false) {
                                $harga = rand(180000, 250000);
                            } elseif (stripos($kelasBus->nama_kelas, "vip") !== false) {
                                $harga = rand(300000, 400000);
                            }

                            \App\Models\JadwalKelasBus::create([
                                "jadwal_id" => $jadwal->id,
                                "kelas_bus_id" => $kelasBus->id,
                                "harga" => $harga,
                            ]);
                        }
                    }
                }
            }
        }

        // Buat contoh tiket dan pembayaran menggunakan jadwal pertama yang tersedia
        $jadwalPertama = array_values($jadwalCollection)[0] ?? null;
        if ($jadwalPertama) {
            $jadwalKelasBusPertama = \App\Models\JadwalKelasBus::where("jadwal_id", $jadwalPertama->id)->first();
            $kursiPertama = \App\Models\Kursi::where("kelas_bus_id", $jadwalKelasBusPertama->kelas_bus_id)->first();

            // Tiket
            $tiket = \App\Models\Tiket::create([
                "user_id" => $user->id,
                "jadwal_kelas_bus_id" => $jadwalKelasBusPertama->id,
                "kursi_id" => $kursiPertama->id,
                "nik" => "3173123456789001",
                "nama_penumpang" => "Penumpang Satu",
                "tanggal_lahir" => "2000-01-01",
                "jenis_kelamin" => "L",
                "nomor_telepon" => "081234567890",
                "email" => "penumpang@example.com",
                "kode_tiket" => "TKT001",
                "harga" => $jadwalKelasBusPertama->harga,
                "status" => "dipesan",
                "waktu_pesan" => now(),
            ]);

            // Pembayaran
            \App\Models\Pembayaran::create([
                "user_id" => $user->id,
                "tiket_id" => $tiket->id,
                "metode" => "midtrans",
                "nominal" => $jadwalKelasBusPertama->harga,
                "status" => "berhasil",
                "waktu_bayar" => now(),
                "kode_transaksi" => "TRX001",
            ]);
        }
    }
}
