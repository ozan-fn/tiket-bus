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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tiket_id')->constrained('tiket')->onDelete('cascade');
            $table->string('metode');
            $table->decimal('nominal', 12, 2);
            $table->enum('status', ['pending', 'berhasil', 'gagal', 'refund'])->default('pending');
            $table->timestamp('waktu_bayar')->nullable();
            $table->string('kode_transaksi')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
