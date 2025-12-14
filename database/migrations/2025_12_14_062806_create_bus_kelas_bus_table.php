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
        Schema::create("bus_kelas_bus", function (Blueprint $table) {
            $table->id();
            $table->foreignId("bus_id")->constrained("bus")->onDelete("cascade");
            $table->foreignId("kelas_bus_id")->constrained("kelas_bus")->onDelete("cascade");
            $table->integer("jumlah_kursi")->nullable();
            $table->timestamps();

            $table->unique(["bus_id", "kelas_bus_id"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("bus_kelas_bus");
    }
};
