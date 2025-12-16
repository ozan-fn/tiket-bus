<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kursi extends Model
{
    protected $table = "kursi";

    protected $fillable = [
        "bus_kelas_bus_id",
        "nomor_kursi",
        "index", // Menambahkan kolom index ke fillable
    ];

    public function busKelasBus(): BelongsTo
    {
        return $this->belongsTo(BusKelasBus::class, "bus_kelas_bus_id");
    }

    public function tikets(): HasMany
    {
        return $this->hasMany(Tiket::class);
    }
}
