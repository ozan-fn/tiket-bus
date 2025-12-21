<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalKelasBus extends Model
{
    protected $table = "jadwal_kelas_bus";
    protected $fillable = ["jadwal_id", "bus_kelas_bus_id", "harga"];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function busKelasBus()
    {
        return $this->belongsTo(BusKelasBus::class);
    }

    public function kelasBus()
    {
        return $this->hasOneThrough(KelasBus::class, BusKelasBus::class, "kelas_bus_id", "id", "bus_kelas_bus_id", "id");
    }

    public function tikets()
    {
        return $this->hasMany(Tiket::class);
    }
}
