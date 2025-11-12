<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelasBus extends Model
{
    protected $table = 'kelas_bus';
    protected $fillable = [
        'bus_id',
        'nama_kelas',
        'posisi',
        'jumlah_kursi',
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
}
