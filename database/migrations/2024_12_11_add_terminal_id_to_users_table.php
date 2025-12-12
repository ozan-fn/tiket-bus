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
            $table->unsignedBigInteger("terminal_id")->nullable()->after("nomor_telepon");
            $table->foreign("terminal_id")->references("id")->on("terminal")->onDelete("set null");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("users", function (Blueprint $table) {
            $table->dropForeign(["terminal_id"]);
            $table->dropColumn("terminal_id");
        });
    }
};
