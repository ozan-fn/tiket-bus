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
        Schema::create('bus_fasilitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id')->constrained('bus')->onDelete('cascade');
            $table->foreignId('fasilitas_id')->constrained('fasilitas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bus_fasilitas');
    }
};
