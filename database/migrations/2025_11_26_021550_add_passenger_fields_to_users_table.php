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
        Schema::table("users", function (Blueprint $table) {
            $table->string("nik", 16)->nullable()->after("email");
            $table->date("tanggal_lahir")->nullable()->after("nik");
            $table
                ->enum("jenis_kelamin", ["L", "P"])
                ->nullable()
                ->after("tanggal_lahir");
            $table->string("nomor_telepon", 20)->nullable()->after("jenis_kelamin");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("users", function (Blueprint $table) {
            $table->dropColumn(["nik", "tanggal_lahir", "jenis_kelamin", "nomor_telepon"]);
        });
    }
};
