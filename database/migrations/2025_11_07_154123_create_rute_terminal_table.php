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
        Schema::create('rute_terminal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rute_id')->constrained('rute')->onDelete('cascade');
            $table->foreignId('terminal_id')->constrained('terminal')->onDelete('cascade');
            $table->integer('urutan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rute_terminal');
    }
};
