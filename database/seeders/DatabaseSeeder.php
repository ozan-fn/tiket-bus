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
        // super_admin: Mengelola seluruh sistem, termasuk admin dan pengaturan global
        // admin: Mengelola data bus, jadwal, user, laporan, dan manajemen sistem
        // operator: Mengelola jadwal bus, validasi tiket, data sopir, dan status bus
        // user: Melakukan pemesanan tiket, melihat jadwal, dan riwayat transaksi
        // sopir: Sopir bus, bisa login jika diperlukan untuk absen, cek jadwal, atau laporan
        // Membuat role dan permission

        Role::firstOrCreate(['name' => 'super_admin']);
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'operator']);
        Role::firstOrCreate(['name' => 'user']);
        Role::firstOrCreate(['name' => 'sopir']);

        $superAdmin = User::factory()->create(['name' => 'Super Admin', 'email' => 'superadmin@example.com']);
        $superAdmin->assignRole('super_admin');
        $admin = User::factory()->create(['name' => 'Admin User', 'email' => 'admin@example.com']);
        $admin->assignRole('admin');
        $operator = User::factory()->create(['name' => 'Operator User', 'email' => 'operator@example.com']);
        $operator->assignRole('operator');
        $user = User::factory()->create(['name' => 'Test User', 'email' => 'user@example.com']);
        $user->assignRole('user');
        $sopirUser = User::factory()->create(['name' => 'Sopir Bus', 'email' => 'sopir@example.com']);
        $sopirUser->assignRole('sopir');

        // Fasilitas
        $ac = \App\Models\Fasilitas::create(['nama' => 'AC']);
        $wifi = \App\Models\Fasilitas::create(['nama' => 'WiFi']);
        $toilet = \App\Models\Fasilitas::create(['nama' => 'Toilet']);

        // Bus
        $bus1 = \App\Models\Bus::create([
            'nama' => 'Bus Mawar',
            'plat_nomor' => 'B 1234 MAW',
            'kapasitas' => 40,
            'status' => 'aktif',
        ]);
        $bus1->fasilitas()->attach([$ac->id, $wifi->id]);

        $bus2 = \App\Models\Bus::create([
            'nama' => 'Bus Melati',
            'plat_nomor' => 'B 5678 MEL',
            'kapasitas' => 32,
            'status' => 'aktif',
        ]);
        $bus2->fasilitas()->attach([$ac->id, $toilet->id]);

        // Kelas Bus dan relasi ke bus
        foreach ([$bus1, $bus2] as $bus) {
            $ekonomi = \App\Models\KelasBus::create([
                'bus_id' => $bus->id,
                'nama_kelas' => 'Ekonomi',
                'posisi' => 'depan',
                'jumlah_kursi' => 20,
            ]);
            $bisnis = \App\Models\KelasBus::create([
                'bus_id' => $bus->id,
                'nama_kelas' => 'Bisnis',
                'posisi' => 'tengah',
                'jumlah_kursi' => 10,
            ]);
            $vip = \App\Models\KelasBus::create([
                'bus_id' => $bus->id,
                'nama_kelas' => 'VIP',
                'posisi' => 'belakang',
                'jumlah_kursi' => 5,
            ]);
        }

        // Terminal
        $terminalJakarta = \App\Models\Terminal::create(['nama_terminal' => 'Terminal Pulo Gebang', 'nama_kota' => 'Jakarta', 'alamat' => 'Jl. Pulo Gebang']);
        $terminalSurabaya = \App\Models\Terminal::create(['nama_terminal' => 'Terminal Bungurasih', 'nama_kota' => 'Surabaya', 'alamat' => 'Jl. Raya Waru']);
        $terminalCirebon = \App\Models\Terminal::create(['nama_terminal' => 'Terminal Harjamukti', 'nama_kota' => 'Cirebon', 'alamat' => 'Jl. Harjamukti']);

        // Rute
        $rute1 = \App\Models\Rute::create(['asal_terminal_id' => $terminalJakarta->id, 'tujuan_terminal_id' => $terminalSurabaya->id]);
        $rute1->terminal()->attach($terminalCirebon->id, ['urutan' => 1]);

        // Sopir
        $sopir = \App\Models\Sopir::create([
            'user_id' => $sopirUser->id,
            'nik' => '3173123456789012',
            'nomor_sim' => 'SIM123456',
            'alamat' => 'Jl. Sopir Mawar',
            'telepon' => '081234567890',
            'tanggal_lahir' => '1980-01-01',
            'status' => 'aktif',
        ]);

        // Jadwal
        $jadwal = \App\Models\Jadwal::create([
            'bus_id' => $bus1->id,
            'sopir_id' => $sopir->id,
            'rute_id' => $rute1->id,
            'tanggal_berangkat' => now()->addDays(1)->toDateString(),
            'jam_berangkat' => '08:00:00',
            'status' => 'tersedia',
        ]);

        // Jadwal Kelas Bus untuk jadwal ini
        $jadwalKelasBus = \App\Models\JadwalKelasBus::create([
            'jadwal_id' => $jadwal->id,
            'kelas_bus_id' => \App\Models\KelasBus::where('bus_id', $bus1->id)->where('nama_kelas', 'Ekonomi')->first()->id,
            'harga' => 150000,
        ]);

        // Tiket
        $tiket = \App\Models\Tiket::create([
            'user_id' => $user->id,
            'jadwal_kelas_bus_id' => $jadwalKelasBus->id,
            'nik' => '3173123456789001',
            'nama_penumpang' => 'Penumpang Satu',
            'tanggal_lahir' => '2000-01-01',
            'jenis_kelamin' => 'L',
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
        // ...existing code...
        // ...existing code...

        // ...existing code...
    }
}
