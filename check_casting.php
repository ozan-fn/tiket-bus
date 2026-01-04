<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== RAW DATABASE VALUES ===\n";
$result = DB::table('jadwal')->where('id', 2)->first();
echo "Tanggal Berangkat (Raw): " . $result->tanggal_berangkat . "\n";
echo "Jam Berangkat (Raw): " . $result->jam_berangkat . "\n";

echo "\n=== CASTING TEST ===\n";
$jadwal = \App\Models\Jadwal::find(2);
echo "After Model Casting:\n";
echo "  tanggal_berangkat: " . $jadwal->tanggal_berangkat . " (type: " . gettype($jadwal->tanggal_berangkat) . ")\n";
echo "  jam_berangkat: " . $jadwal->jam_berangkat . " (type: " . gettype($jadwal->jam_berangkat) . ")\n";

echo "\n=== STRING COMPARISON TEST ===\n";
$today = now()->toDateString();
$time = now()->toTimeString();
echo "Today: " . $today . "\n";
echo "Current Time: " . $time . "\n";

$tanggal_str = (string) $result->tanggal_berangkat;
$jam_str = (string) $result->jam_berangkat;

echo "Tanggal as string: '" . $tanggal_str . "'\n";
echo "Jam as string: '" . $jam_str . "'\n";

echo "\nComparison tanggal > today:\n";
var_dump($tanggal_str > $today);

echo "\nComparison jam >= time (same day):\n";
var_dump($jam_str >= $time);
