<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tambah kolom foto profile di tabel users
        Schema::table('users', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('email');
        });

        // Tambah kolom bukti pembayaran di tabel pembayaran
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->string('bukti_pembayaran')->nullable()->after('kode_transaksi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('photo');
        });

        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn('bukti_pembayaran');
        });
    }
};
