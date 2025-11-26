<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Auto-release booking yang expired (belum dibayar dalam 30 menit)
Schedule::call(function () {
    $releasedCount = \App\Models\Tiket::where('status', 'dipesan')
        ->where('created_at', '<', now()->subMinutes(30))
        ->update(['status' => 'batal']);

    if ($releasedCount > 0) {
        \Illuminate\Support\Facades\Log::info("Released {$releasedCount} expired bookings");
    }
})->everyMinute()->name('release-expired-bookings');