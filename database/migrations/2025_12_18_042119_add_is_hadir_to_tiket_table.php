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
        Schema::table("tiket", function (Blueprint $table) {
            $table->boolean("is_hadir")->default(false);
            $table->timestamp("waktu_scan")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("tiket", function (Blueprint $table) {
            $table->dropColumn(["is_hadir", "waktu_scan"]);
        });
    }
};
