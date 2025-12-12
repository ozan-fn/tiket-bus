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
        Schema::create("jadwal", function (Blueprint $table) {
            $table->id();
            $table->foreignId("bus_id")->constrained("bus")->onDelete("cascade");
            $table->foreignId("sopir_id")->constrained("sopir")->onDelete("cascade");
            $table->foreignId("conductor_id")->nullable()->constrained("sopir")->onDelete("set null");
            $table->foreignId("rute_id")->constrained("rute")->onDelete("cascade");
            $table->date("tanggal_berangkat");
            $table->time("jam_berangkat");
            $table->enum("status", ["tersedia", "berangkat", "selesai", "batal", "aktif", "tidak_aktif"])->default("tersedia");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("jadwal");
    }
};
