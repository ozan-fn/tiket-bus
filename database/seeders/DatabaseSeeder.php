<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
        // agent: Mengelola data bus, jadwal, user, laporan, dan manajemen sistem
        // passenger: Melakukan pemesanan tiket, melihat jadwal, dan riwayat transaksi
        // driver: Sopir bus, bisa login jika diperlukan untuk absen, cek jadwal, atau laporan
        // Membuat role dan permission

        Role::firstOrCreate(['name' => 'owner']);
        Role::firstOrCreate(['name' => 'agent']);
        Role::firstOrCreate(['name' => 'passenger']);
        Role::firstOrCreate(['name' => 'driver']);


        $owner = User::factory()->create([
            'name' => 'Owner User',
            'email' => 'owner@example.com'
        ]);
        $owner->assignRole('owner');

        $agent = User::factory()->create([
            'name' => 'Agent User',
            'email' => 'agent@example.com'
        ]);
        $agent->assignRole('agent');

        $passenger = User::factory()->create([
            'name' => 'Passenger User',
            'email' => 'passenger@example.com'
        ]);
        $passenger->assignRole('passenger');

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@example.com'
        ]);
        $user->assignRole('passenger');

        // Fasilitas
        $ac = \App\Models\Fasilitas::create(['nama' => 'AC', 'icon' => 'snowflake']);
        $wifi = \App\Models\Fasilitas::create(['nama' => 'WiFi', 'icon' => 'wifi']);
        $toilet = \App\Models\Fasilitas::create(['nama' => 'Toilet', 'icon' => 'toilet']);
        $tv = \App\Models\Fasilitas::create(['nama' => 'TV', 'icon' => 'tv']);
        $snack = \App\Models\Fasilitas::create(['nama' => 'Makanan Ringan', 'icon' => 'utensils']);
        $usb = \App\Models\Fasilitas::create(['nama' => 'Pengisian USB', 'icon' => 'plug']);
        $recliner = \App\Models\Fasilitas::create(['nama' => 'Kursi Recliner', 'icon' => 'chair']);
        $entertainment = \App\Models\Fasilitas::create(['nama' => 'Sistem Hiburan', 'icon' => 'gamepad']);
        $readingLight = \App\Models\Fasilitas::create(['nama' => 'Lampu Baca', 'icon' => 'lightbulb']);
        $blanket = \App\Models\Fasilitas::create(['nama' => 'Selimut', 'icon' => 'bed']);
        $pillow = \App\Models\Fasilitas::create(['nama' => 'Bantal', 'icon' => 'couch']);
        $water = \App\Models\Fasilitas::create(['nama' => 'Air Mineral', 'icon' => 'bottle-water']);
        $luggage = \App\Models\Fasilitas::create(['nama' => 'Penyimpanan Bagasi', 'icon' => 'suitcase']);
        $firstAid = \App\Models\Fasilitas::create(['nama' => 'Kotak P3K', 'icon' => 'medkit']);
        $gps = \App\Models\Fasilitas::create(['nama' => 'Pelacakan GPS', 'icon' => 'map-marker']);
        $cctv = \App\Models\Fasilitas::create(['nama' => 'CCTV', 'icon' => 'video-camera']);
        $powerOutlet = \App\Models\Fasilitas::create(['nama' => 'Stopkontak', 'icon' => 'bolt']);
        $legroom = \App\Models\Fasilitas::create(['nama' => 'Ruang Kaki Tambahan', 'icon' => 'arrows-alt']);
        $adjustableSeat = \App\Models\Fasilitas::create(['nama' => 'Kursi yang Dapat Disesuaikan', 'icon' => 'sliders']);
        $climateControl = \App\Models\Fasilitas::create(['nama' => 'Kontrol Iklim', 'icon' => 'thermometer']);
        $onboardKitchen = \App\Models\Fasilitas::create(['nama' => 'Dapur di Dalam Bus', 'icon' => 'kitchen-set']);
        $shower = \App\Models\Fasilitas::create(['nama' => 'Kamar Mandi dengan Shower', 'icon' => 'shower']);
        $massage = \App\Models\Fasilitas::create(['nama' => 'Kursi Pijat', 'icon' => 'hand-sparkles']);
        $audio = \App\Models\Fasilitas::create(['nama' => 'Sistem Audio', 'icon' => 'volume-up']);
        $coffee = \App\Models\Fasilitas::create(['nama' => 'Layanan Kopi/Teh', 'icon' => 'coffee']);
        $newspaper = \App\Models\Fasilitas::create(['nama' => 'Koran/Majalah', 'icon' => 'newspaper']);

        // Bus
        $bus1 = \App\Models\Bus::create([
            'nama' => 'Bus Mawar',
            'plat_nomor' => 'B 1234 MAW',
            'kapasitas' => 40,
            'status' => 'aktif',
        ]);
        $bus1->fasilitas()->attach([$ac->id, $wifi->id, $toilet->id, $tv->id, $snack->id, $usb->id, $recliner->id, $entertainment->id, $readingLight->id, $blanket->id, $pillow->id, $water->id, $luggage->id, $firstAid->id, $gps->id, $cctv->id, $powerOutlet->id, $legroom->id, $adjustableSeat->id, $climateControl->id, $onboardKitchen->id, $shower->id, $massage->id, $audio->id, $coffee->id, $newspaper->id]);

        $bus2 = \App\Models\Bus::create([
            'nama' => 'Bus Melati',
            'plat_nomor' => 'B 5678 MEL',
            'kapasitas' => 32,
            'status' => 'aktif',
        ]);
        $bus2->fasilitas()->attach([$ac->id, $toilet->id, $wifi->id, $tv->id, $snack->id, $usb->id, $recliner->id, $entertainment->id, $readingLight->id, $blanket->id, $pillow->id, $water->id, $luggage->id, $firstAid->id, $gps->id, $cctv->id, $powerOutlet->id, $legroom->id, $adjustableSeat->id, $climateControl->id, $onboardKitchen->id, $shower->id, $massage->id, $audio->id, $coffee->id, $newspaper->id]);

        $bus3 = \App\Models\Bus::create([
            'nama' => 'Bus Anggrek',
            'plat_nomor' => 'B 9012 ANG',
            'kapasitas' => 35,
            'status' => 'aktif',
        ]);
        $bus3->fasilitas()->attach([$ac->id, $tv->id, $wifi->id, $toilet->id, $snack->id, $usb->id, $recliner->id, $entertainment->id, $readingLight->id, $blanket->id, $pillow->id, $water->id, $luggage->id, $firstAid->id, $gps->id, $cctv->id, $powerOutlet->id, $legroom->id, $adjustableSeat->id, $climateControl->id, $onboardKitchen->id, $shower->id, $massage->id, $audio->id, $coffee->id, $newspaper->id]);

        $bus4 = \App\Models\Bus::create([
            'nama' => 'Bus Tulip',
            'plat_nomor' => 'B 3456 TUL',
            'kapasitas' => 38,
            'status' => 'aktif',
        ]);
        $bus4->fasilitas()->attach([$wifi->id, $snack->id, $ac->id, $toilet->id, $tv->id, $usb->id, $recliner->id, $entertainment->id, $readingLight->id, $blanket->id, $pillow->id, $water->id, $luggage->id, $firstAid->id, $gps->id, $cctv->id, $powerOutlet->id, $legroom->id, $adjustableSeat->id, $climateControl->id, $onboardKitchen->id, $shower->id, $massage->id, $audio->id, $coffee->id, $newspaper->id]);

        $bus5 = \App\Models\Bus::create([
            'nama' => 'Bus Sakura',
            'plat_nomor' => 'B 7890 SAK',
            'kapasitas' => 30,
            'status' => 'aktif',
        ]);
        $bus5->fasilitas()->attach([$toilet->id, $usb->id, $ac->id, $wifi->id, $tv->id, $snack->id, $recliner->id, $entertainment->id, $readingLight->id, $blanket->id, $pillow->id, $water->id, $luggage->id, $firstAid->id, $gps->id, $cctv->id, $powerOutlet->id, $legroom->id, $adjustableSeat->id, $climateControl->id, $onboardKitchen->id, $shower->id, $massage->id, $audio->id, $coffee->id, $newspaper->id]);

        // Kelas Bus
        $kelasBus1Ekonomi = \App\Models\KelasBus::create([
            'bus_id' => $bus1->id,
            'nama_kelas' => 'Ekonomi',
            'deskripsi' => 'Kelas ekonomi di lantai bawah dengan kursi standar',
            'jumlah_kursi' => 20,
        ]);

        $kelasBus1Bisnis = \App\Models\KelasBus::create([
            'bus_id' => $bus1->id,
            'nama_kelas' => 'Bisnis',
            'deskripsi' => 'Kelas bisnis di lantai atas dengan kursi lebih nyaman',
            'jumlah_kursi' => 20,
        ]);

        $kelasBus2Ekonomi = \App\Models\KelasBus::create([
            'bus_id' => $bus2->id,
            'nama_kelas' => 'Ekonomi',
            'deskripsi' => 'Kelas ekonomi di lantai bawah dengan kursi standar',
            'jumlah_kursi' => 16,
        ]);

        $kelasBus2Bisnis = \App\Models\KelasBus::create([
            'bus_id' => $bus2->id,
            'nama_kelas' => 'Bisnis',
            'deskripsi' => 'Kelas bisnis di lantai atas dengan kursi lebih nyaman',
            'jumlah_kursi' => 16,
        ]);

        $kelasBus3Ekonomi = \App\Models\KelasBus::create([
            'bus_id' => $bus3->id,
            'nama_kelas' => 'Ekonomi',
            'deskripsi' => 'Kelas ekonomi di lantai bawah dengan kursi standar',
            'jumlah_kursi' => 18,
        ]);

        $kelasBus3VIP = \App\Models\KelasBus::create([
            'bus_id' => $bus3->id,
            'nama_kelas' => 'VIP',
            'deskripsi' => 'Kelas VIP di lantai atas dengan kursi premium dan fasilitas eksklusif',
            'jumlah_kursi' => 17,
        ]);

        $kelasBus4Ekonomi = \App\Models\KelasBus::create([
            'bus_id' => $bus4->id,
            'nama_kelas' => 'Ekonomi',
            'deskripsi' => 'Kelas ekonomi di lantai bawah dengan kursi standar',
            'jumlah_kursi' => 19,
        ]);

        $kelasBus4Bisnis = \App\Models\KelasBus::create([
            'bus_id' => $bus4->id,
            'nama_kelas' => 'Bisnis',
            'deskripsi' => 'Kelas bisnis di lantai atas dengan kursi lebih nyaman',
            'jumlah_kursi' => 19,
        ]);

        $kelasBus5Ekonomi = \App\Models\KelasBus::create([
            'bus_id' => $bus5->id,
            'nama_kelas' => 'Ekonomi',
            'deskripsi' => 'Kelas ekonomi di lantai bawah dengan kursi standar',
            'jumlah_kursi' => 15,
        ]);

        $kelasBus5VIP = \App\Models\KelasBus::create([
            'bus_id' => $bus5->id,
            'nama_kelas' => 'VIP',
            'deskripsi' => 'Kelas VIP di lantai atas dengan kursi premium dan fasilitas eksklusif',
            'jumlah_kursi' => 15,
        ]);

        // Kursi untuk setiap kelas bus
        $kelasArray = [
            $kelasBus1Ekonomi,
            $kelasBus1Bisnis,
            $kelasBus2Ekonomi,
            $kelasBus2Bisnis,
            $kelasBus3Ekonomi,
            $kelasBus3VIP,
            $kelasBus4Ekonomi,
            $kelasBus4Bisnis,
            $kelasBus5Ekonomi,
            $kelasBus5VIP,
        ];

        foreach ($kelasArray as $kelas) {
            for ($i = 1; $i <= $kelas->jumlah_kursi; $i++) {
                \App\Models\Kursi::create([
                    'kelas_bus_id' => $kelas->id,
                    'nomor_kursi' => $i,
                    'index' => $i - 1,
                ]);
            }
        }

        // Terminal
        $terminalJakarta = \App\Models\Terminal::create(['nama_terminal' => 'Terminal Pulo Gebang', 'nama_kota' => 'Jakarta', 'alamat' => 'Jl. Pulo Gebang']);
        $terminalSurabaya = \App\Models\Terminal::create(['nama_terminal' => 'Terminal Bungurasih', 'nama_kota' => 'Surabaya', 'alamat' => 'Jl. Raya Waru']);
        $terminalCirebon = \App\Models\Terminal::create(['nama_terminal' => 'Terminal Harjamukti', 'nama_kota' => 'Cirebon', 'alamat' => 'Jl. Harjamukti']);
        $terminalBandung = \App\Models\Terminal::create(['nama_terminal' => 'Terminal Leuwi Panjang', 'nama_kota' => 'Bandung', 'alamat' => 'Jl. Soekarno-Hatta']);
        $terminalSemarang = \App\Models\Terminal::create(['nama_terminal' => 'Terminal Terboyo', 'nama_kota' => 'Semarang', 'alamat' => 'Jl. Terboyo']);
        $terminalYogyakarta = \App\Models\Terminal::create(['nama_terminal' => 'Terminal Giwangan', 'nama_kota' => 'Yogyakarta', 'alamat' => 'Jl. Imogiri Timur']);
        $terminalMalang = \App\Models\Terminal::create(['nama_terminal' => 'Terminal Arjosari', 'nama_kota' => 'Malang', 'alamat' => 'Jl. Raden Intan']);
        $terminalDenpasar = \App\Models\Terminal::create(['nama_terminal' => 'Terminal Ubung', 'nama_kota' => 'Denpasar', 'alamat' => 'Jl. Cokroaminoto']);
        $terminalMedan = \App\Models\Terminal::create(['nama_terminal' => 'Terminal Amplas', 'nama_kota' => 'Medan', 'alamat' => 'Jl. Sisingamangaraja']);
        $terminalPalembang = \App\Models\Terminal::create(['nama_terminal' => 'Terminal Karya Jaya', 'nama_kota' => 'Palembang', 'alamat' => 'Jl. Kol. H. Burlian']);
        $terminalMakassar = \App\Models\Terminal::create(['nama_terminal' => 'Terminal Daya', 'nama_kota' => 'Makassar', 'alamat' => 'Jl. Perintis Kemerdekaan']);

        // Rute
        $rute1 = \App\Models\Rute::create(['asal_terminal_id' => $terminalJakarta->id, 'tujuan_terminal_id' => $terminalSurabaya->id]);
        $rute1->terminal()->attach($terminalCirebon->id, ['urutan' => 1]);

        $rute2 = \App\Models\Rute::create(['asal_terminal_id' => $terminalBandung->id, 'tujuan_terminal_id' => $terminalYogyakarta->id]);
        $rute2->terminal()->attach($terminalSemarang->id, ['urutan' => 1]);

        $rute3 = \App\Models\Rute::create(['asal_terminal_id' => $terminalSurabaya->id, 'tujuan_terminal_id' => $terminalDenpasar->id]);
        $rute3->terminal()->attach($terminalMalang->id, ['urutan' => 1]);

        $rute4 = \App\Models\Rute::create(['asal_terminal_id' => $terminalMedan->id, 'tujuan_terminal_id' => $terminalPalembang->id]);
        $rute4->terminal()->attach($terminalJakarta->id, ['urutan' => 1]);

        $rute5 = \App\Models\Rute::create(['asal_terminal_id' => $terminalMakassar->id, 'tujuan_terminal_id' => $terminalDenpasar->id]);
        $rute5->terminal()->attach($terminalSurabaya->id, ['urutan' => 1]);

        // Rute tambahan
        $rute6 = \App\Models\Rute::create(['asal_terminal_id' => $terminalBandung->id, 'tujuan_terminal_id' => $terminalSurabaya->id]);
        $rute6->terminal()->attach($terminalCirebon->id, ['urutan' => 1]);

        $rute7 = \App\Models\Rute::create(['asal_terminal_id' => $terminalJakarta->id, 'tujuan_terminal_id' => $terminalYogyakarta->id]);
        $rute7->terminal()->attach($terminalSemarang->id, ['urutan' => 1]);

        $rute8 = \App\Models\Rute::create(['asal_terminal_id' => $terminalMedan->id, 'tujuan_terminal_id' => $terminalMakassar->id]);
        $rute8->terminal()->attach($terminalPalembang->id, ['urutan' => 1]);

        $rute9 = \App\Models\Rute::create(['asal_terminal_id' => $terminalDenpasar->id, 'tujuan_terminal_id' => $terminalBandung->id]);
        $rute9->terminal()->attach($terminalSurabaya->id, ['urutan' => 1]);

        $rute10 = \App\Models\Rute::create(['asal_terminal_id' => $terminalMakassar->id, 'tujuan_terminal_id' => $terminalJakarta->id]);
        $rute10->terminal()->attach($terminalSurabaya->id, ['urutan' => 1]);

        // Membuat akun sopir untuk setiap bus
        $sopirBus1 = User::factory()->create([
            'name' => 'Sopir Bus Mawar',
            'email' => 'sopir.mawar@example.com',
        ]);
        $sopirBus1->assignRole('driver');

        $sopirBus2 = User::factory()->create([
            'name' => 'Sopir Bus Melati',
            'email' => 'sopir.melati@example.com',
        ]);
        $sopirBus2->assignRole('driver');

        $sopirBus3 = User::factory()->create([
            'name' => 'Sopir Bus Anggrek',
            'email' => 'sopir.anggrek@example.com',
        ]);
        $sopirBus3->assignRole('driver');

        $sopirBus4 = User::factory()->create([
            'name' => 'Sopir Bus Tulip',
            'email' => 'sopir.tulip@example.com',
        ]);
        $sopirBus4->assignRole('driver');

        $sopirBus5 = User::factory()->create([
            'name' => 'Sopir Bus Sakura',
            'email' => 'sopir.sakura@example.com',
        ]);
        $sopirBus5->assignRole('driver');

        // Sopir
        $sopir = \App\Models\Sopir::create([
            'user_id' => $sopirBus1->id, // Adjusted to use the correct driver account
            'nik' => '3173123456789012',
            'nomor_sim' => 'SIM123456',
            'alamat' => 'Jl. Sopir Mawar',
            'telepon' => '081234567890',
            'tanggal_lahir' => '1980-01-01',
            'status' => 'aktif',
        ]);

        $sopirBus2 = \App\Models\Sopir::create([
            'user_id' => $sopirBus2->id, // Adjusted to use the correct driver account
            'nik' => '3173123456789013',
            'nomor_sim' => 'SIM123457',
            'alamat' => 'Jl. Sopir Melati',
            'telepon' => '081234567891',
            'tanggal_lahir' => '1981-02-02',
            'status' => 'aktif',
        ]);

        $sopirBus3 = \App\Models\Sopir::create([
            'user_id' => $sopirBus3->id, // Adjusted to use the correct driver account
            'nik' => '3173123456789014',
            'nomor_sim' => 'SIM123458',
            'alamat' => 'Jl. Sopir Anggrek',
            'telepon' => '081234567892',
            'tanggal_lahir' => '1982-03-03',
            'status' => 'aktif',
        ]);

        $sopirBus4 = \App\Models\Sopir::create([
            'user_id' => $sopirBus4->id, // Adjusted to use the correct driver account
            'nik' => '3173123456789015',
            'nomor_sim' => 'SIM123459',
            'alamat' => 'Jl. Sopir Tulip',
            'telepon' => '081234567893',
            'tanggal_lahir' => '1983-04-04',
            'status' => 'aktif',
        ]);

        $sopirBus5 = \App\Models\Sopir::create([
            'user_id' => $sopirBus5->id, // Adjusted to use the correct driver account
            'nik' => '3173123456789016',
            'nomor_sim' => 'SIM123460',
            'alamat' => 'Jl. Sopir Sakura',
            'telepon' => '081234567894',
            'tanggal_lahir' => '1984-05-05',
            'status' => 'aktif',
        ]);

        // Jadwal tambahan untuk setiap bus dan rute
        $jadwal1 = \App\Models\Jadwal::create([
            'bus_id' => $bus1->id,
            'sopir_id' => $sopir->id,
            'rute_id' => $rute1->id,
            'tanggal_berangkat' => now()->addDays(2)->toDateString(),
            'jam_berangkat' => '09:00:00',
            'status' => 'tersedia',
        ]);

        $jadwal2 = \App\Models\Jadwal::create([
            'bus_id' => $bus2->id,
            'sopir_id' => $sopirBus2->id,
            'rute_id' => $rute2->id,
            'tanggal_berangkat' => now()->addDays(3)->toDateString(),
            'jam_berangkat' => '10:00:00',
            'status' => 'tersedia',
        ]);

        $jadwal3 = \App\Models\Jadwal::create([
            'bus_id' => $bus3->id,
            'sopir_id' => $sopirBus3->id,
            'rute_id' => $rute3->id,
            'tanggal_berangkat' => now()->addDays(4)->toDateString(),
            'jam_berangkat' => '11:00:00',
            'status' => 'tersedia',
        ]);

        $jadwal4 = \App\Models\Jadwal::create([
            'bus_id' => $bus4->id,
            'sopir_id' => $sopirBus4->id,
            'rute_id' => $rute4->id,
            'tanggal_berangkat' => now()->addDays(5)->toDateString(),
            'jam_berangkat' => '12:00:00',
            'status' => 'tersedia',
        ]);

        $jadwal5 = \App\Models\Jadwal::create([
            'bus_id' => $bus5->id,
            'sopir_id' => $sopirBus5->id,
            'rute_id' => $rute5->id,
            'tanggal_berangkat' => now()->addDays(6)->toDateString(),
            'jam_berangkat' => '13:00:00',
            'status' => 'tersedia',
        ]);

        // Jadwal Kelas Bus (menghubungkan jadwal dengan kelas bus dan harga)
        $jadwalKelasBus1Ekonomi = \App\Models\JadwalKelasBus::create([
            'jadwal_id' => $jadwal1->id,
            'kelas_bus_id' => $kelasBus1Ekonomi->id,
            'harga' => 150000,
        ]);

        $jadwalKelasBus1Bisnis = \App\Models\JadwalKelasBus::create([
            'jadwal_id' => $jadwal1->id,
            'kelas_bus_id' => $kelasBus1Bisnis->id,
            'harga' => 250000,
        ]);

        $jadwalKelasBus2Ekonomi = \App\Models\JadwalKelasBus::create([
            'jadwal_id' => $jadwal2->id,
            'kelas_bus_id' => $kelasBus2Ekonomi->id,
            'harga' => 120000,
        ]);

        $jadwalKelasBus2Bisnis = \App\Models\JadwalKelasBus::create([
            'jadwal_id' => $jadwal2->id,
            'kelas_bus_id' => $kelasBus2Bisnis->id,
            'harga' => 200000,
        ]);

        $jadwalKelasBus3Ekonomi = \App\Models\JadwalKelasBus::create([
            'jadwal_id' => $jadwal3->id,
            'kelas_bus_id' => $kelasBus3Ekonomi->id,
            'harga' => 180000,
        ]);

        $jadwalKelasBus3VIP = \App\Models\JadwalKelasBus::create([
            'jadwal_id' => $jadwal3->id,
            'kelas_bus_id' => $kelasBus3VIP->id,
            'harga' => 350000,
        ]);

        // Ambil kursi pertama dari kelas ekonomi bus 1 untuk contoh tiket
        $kursiEkonomi = \App\Models\Kursi::where('kelas_bus_id', $kelasBus1Ekonomi->id)->first();

        // Tiket
        $tiket = \App\Models\Tiket::create([
            'user_id' => $user->id,
            'jadwal_kelas_bus_id' => $jadwalKelasBus1Ekonomi->id,
            'kursi_id' => $kursiEkonomi->id,
            'nik' => '3173123456789001',
            'nama_penumpang' => 'Penumpang Satu',
            'tanggal_lahir' => '2000-01-01',
            'jenis_kelamin' => 'L',
            'nomor_telepon' => '081234567890',
            'email' => 'penumpang@example.com',
            'kode_tiket' => 'TKT001',
            'harga' => 150000,
            'status' => 'dipesan',
            'waktu_pesan' => now(),
        ]);

        // Pembayaran
        \App\Models\Pembayaran::create([
            'user_id' => $user->id,
            'tiket_id' => $tiket->id,
            'metode' => 'midtrans',
            'nominal' => 150000,
            'status' => 'berhasil',
            'waktu_bayar' => now(),
            'kode_transaksi' => 'TRX001',
        ]);
    }
}
