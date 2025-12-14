<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelasBus extends Model
{
    protected $table = "kelas_bus";
    protected $fillable = ["nama_kelas", "deskripsi", "jumlah_kursi"];

    public function kursi()
    {
        return $this->hasMany(Kursi::class);
    }

    public function jadwalKelasBus()
    {
        return $this->hasMany(JadwalKelasBus::class);
    }
}
