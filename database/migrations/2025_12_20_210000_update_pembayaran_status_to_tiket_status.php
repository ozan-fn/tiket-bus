<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * Updates the pembayaran.status enum to match tiket status variants.
     * Maps existing statuses: pending -> dipesan, berhasil -> dibayar, gagal -> batal, refund -> selesai
     */
    public function up(): void
    {
        // Alter enum to include new values first to avoid truncation during updates
        DB::statement("ALTER TABLE `pembayaran` MODIFY `status` ENUM('pending','berhasil','gagal','refund','dipesan','dibayar','batal','selesai') NOT NULL DEFAULT 'pending'");

        // Map existing statuses to new ones
        DB::table("pembayaran")
            ->where("status", "pending")
            ->update(["status" => "dipesan"]);
        DB::table("pembayaran")
            ->where("status", "berhasil")
            ->update(["status" => "dibayar"]);
        DB::table("pembayaran")
            ->where("status", "gagal")
            ->update(["status" => "batal"]);
        DB::table("pembayaran")
            ->where("status", "refund")
            ->update(["status" => "selesai"]);

        // Alter enum to final new values
        DB::statement("ALTER TABLE `pembayaran` MODIFY `status` ENUM('dipesan','dibayar','batal','selesai') NOT NULL DEFAULT 'dipesan'");
    }

    /**
     * Reverse the migrations.
     *
     * Reverts the enum back to original and maps statuses back.
     */
    public function down(): void
    {
        // Alter enum to include old values first to avoid truncation during updates
        DB::statement("ALTER TABLE `pembayaran` MODIFY `status` ENUM('pending','berhasil','gagal','refund','dipesan','dibayar','batal','selesai') NOT NULL DEFAULT 'pending'");

        // Map back to original statuses
        DB::table("pembayaran")
            ->where("status", "dipesan")
            ->update(["status" => "pending"]);
        DB::table("pembayaran")
            ->where("status", "dibayar")
            ->update(["status" => "berhasil"]);
        DB::table("pembayaran")
            ->where("status", "batal")
            ->update(["status" => "gagal"]);
        DB::table("pembayaran")
            ->where("status", "selesai")
            ->update(["status" => "refund"]);

        // Revert enum to original values
        DB::statement("ALTER TABLE `pembayaran` MODIFY `status` ENUM('pending','berhasil','gagal','refund') NOT NULL DEFAULT 'pending'");
    }
};
