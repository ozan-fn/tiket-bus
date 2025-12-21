<?php

namespace App\Console\Commands;

use App\Models\Jadwal;
use App\Models\Tiket;
use Illuminate\Console\Command;

class ExpireSchedules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "schedules:expire";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Expire schedules and tickets that have passed their departure time";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Starting to expire schedules and tickets...");

        // Expire Jadwal where status is aktif or tersedia and departure time is past
        $expiredJadwalCount = Jadwal::whereIn("status", ["aktif", "tersedia"])
            ->expired()
            ->update(["status" => "selesai"]);

        $this->info("Expired {$expiredJadwalCount} schedules.");

        // Expire Tiket where status is dipesan and their jadwal is expired (do NOT auto-expire already paid tickets)
        $expiredTiketIds = Tiket::whereIn("status", ["dipesan"])
            ->whereHas("jadwalKelasBus.jadwal", fn($query) => $query->expired())
            ->pluck("id");

        $expiredTiketCount = Tiket::whereIn("id", $expiredTiketIds)->update(["status" => "batal"]);

        // Expire Pembayaran terkait (hanya yang masih berstatus 'dipesan')
        // Pembayaran dengan status 'dibayar' tidak diubah agar paid tickets tetap valid
        $expiredPembayaranCount = \App\Models\Pembayaran::whereIn("tiket_id", $expiredTiketIds)
            ->whereIn("status", ["dipesan"])
            ->update(["status" => "batal"]);

        $this->info("Expired {$expiredTiketCount} tickets and {$expiredPembayaranCount} payments.");

        $this->info("Expiration process completed.");
    }
}
