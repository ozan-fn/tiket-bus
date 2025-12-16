<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelasBus extends Model
{
    protected $table = "kelas_bus";
    protected $fillable = ["nama_kelas", "deskripsi"];

    public function busKelasBus()
    {
        return $this->hasMany(BusKelasBus::class);
    }

    public function kursi()
    {
        return $this->hasManyThrough(Kursi::class, BusKelasBus::class);
    }

    public function jadwalKelasBus()
    {
        return $this->hasManyThrough(JadwalKelasBus::class, BusKelasBus::class);
    }
}
