<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusKelasBus extends Model
{
    protected $table = "bus_kelas_bus";
    protected $fillable = ["bus_id", "kelas_bus_id", "jumlah_kursi"];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function kelasBus()
    {
        return $this->belongsTo(KelasBus::class);
    }

    public function kursi()
    {
        return $this->hasMany(Kursi::class);
    }

    public function jadwalKelasBus()
    {
        return $this->hasMany(JadwalKelasBus::class);
    }
}
