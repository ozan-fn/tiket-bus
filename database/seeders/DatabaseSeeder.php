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


        // Membuat user dan assign role
        $superAdmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
        ]);
        $superAdmin->assignRole('super_admin');

        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);
        $admin->assignRole('admin');

        $operator = User::factory()->create([
            'name' => 'Operator User',
            'email' => 'operator@example.com',
        ]);
        $operator->assignRole('operator');

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@example.com',
        ]);
        $user->assignRole('user');

        $sopir = User::factory()->create([
            'name' => 'Sopir Bus',
            'email' => 'sopir@example.com',
        ]);
        $sopir->assignRole('sopir');

    }
}
