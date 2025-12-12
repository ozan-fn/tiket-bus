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
        Schema::create("kursi", function (Blueprint $table) {
            $table->id();
            $table->foreignId("kelas_bus_id")->constrained("kelas_bus")->onDelete("cascade");
            $table->string("nomor_kursi"); // Contoh: "A1", "B5", "15"
            $table->integer("index"); // Kolom untuk menyimpan urutan kursi
            $table->timestamps();

            $table->unique(["kelas_bus_id", "nomor_kursi"]);
        });

        Schema::table("tiket", function (Blueprint $table) {
            $table->foreign("kursi_id")->references("id")->on("kursi")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("tiket", function (Blueprint $table) {
            $table->dropForeignKeyIfExists(["kursi_id"]);
        });

        Schema::dropIfExists("kursi");
    }
};
