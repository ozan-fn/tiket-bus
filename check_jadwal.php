<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Jadwal;

echo "=== CHECKING JADWAL ID 2 ===\n";
$jadwal = Jadwal::find(2);
if ($jadwal) {
    echo "ID: " . $jadwal->id . "\n";
    echo "Status: " . $jadwal->status . "\n";
    echo "Tanggal Berangkat: " . $jadwal->tanggal_berangkat . "\n";
    echo "Jam Berangkat: " . $jadwal->jam_berangkat . "\n";
    echo "Rute ID: " . $jadwal->rute_id . "\n";
    echo "Bus ID: " . $jadwal->bus_id . "\n";
    echo "\n=== CURRENT TIME ===\n";
    echo "Now: " . now()->toDateTimeString() . "\n";
    echo "Today: " . now()->toDateString() . "\n";
    echo "Current Time: " . now()->toTimeString() . "\n";
    echo "\n=== JADWAL KELAS BUS ===\n";
    echo "Has JadwalKelasBus: " . ($jadwal->jadwalKelasBus()->exists() ? 'Yes' : 'No') . "\n";
    echo "Count: " . $jadwal->jadwalKelasBus()->count() . "\n";
    echo "\n=== ACTIVE SCOPE TEST ===\n";
    echo "Passes Active Scope: " . (Jadwal::where('id', 2)->active()->exists() ? 'Yes' : 'No') . "\n";
    echo "Active + Has Pricing: " . (Jadwal::where('id', 2)->active()->has('jadwalKelasBus')->exists() ? 'Yes' : 'No') . "\n";

    echo "\n=== FULL CONTROLLER QUERY ===\n";
    $count = Jadwal::with(["bus.fasilitas", "sopir.user", "conductor.user", "rute.asalTerminal", "rute.tujuanTerminal", "jadwalKelasBus.kelasBus"])
        ->active()
        ->has("jadwalKelasBus")
        ->orderBy("tanggal_berangkat", "asc")
        ->orderBy("jam_berangkat", "asc")
        ->count();
    echo "Total from controller query: " . $count . "\n";
} else {
    echo "Jadwal ID 2 not found!\n";
}
