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
        Schema::create("tiket", function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users")->onDelete("cascade");
            $table->foreignId("jadwal_kelas_bus_id")->constrained("jadwal_kelas_bus")->onDelete("cascade");
            $table->unsignedBigInteger("kursi_id")->nullable();
            $table->foreign("kursi_id")->references("id")->on("kursi")->onDelete("cascade");
            $table->string("nik");
            $table->string("nama_penumpang");
            $table->date("tanggal_lahir")->nullable();
            $table->enum("jenis_kelamin", ["L", "P"]);
            $table->string("kode_tiket")->unique();
            $table->decimal("harga", 12, 2);
            $table->enum("status", ["dipesan", "dibayar", "batal", "selesai"])->default("dipesan");
            $table->timestamp("waktu_pesan")->useCurrent();
            $table->string("nomor_telepon")->nullable();
            $table->string("email")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("tiket");
    }
};
