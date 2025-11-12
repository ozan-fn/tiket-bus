<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalKelasBus extends Model
{
    protected $table = 'jadwal_kelas_bus';
    protected $fillable = [
        'jadwal_id',
        'kelas_bus_id',
        'harga',
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function kelasBus()
    {
        return $this->belongsTo(KelasBus::class);
    }
}
