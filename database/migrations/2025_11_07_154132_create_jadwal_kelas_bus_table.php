<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jadwal_kelas_bus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained('jadwal')->onDelete('cascade');
            $table->foreignId('kelas_bus_id')->constrained('kelas_bus')->onDelete('cascade');
            $table->integer('harga');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('jadwal_kelas_bus');
    }
};
