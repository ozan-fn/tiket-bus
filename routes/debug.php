<?php

use Illuminate\Support\Facades\Route;
use Carbon\Carbon;

Route::get('/debug-time', function () {
    return [
        'now' => now()->toDateTimeString(),
        'timezone' => config('app.timezone'),
        'today' => now()->toDateString(),
        'time' => now()->toTimeString(),
        'db_time' => DB::select('SELECT NOW() as now')[0]->now,
    ];
});
