<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Bus
 * 
 * @property int $id
 * @property string $nama
 * @property string $plat_nomor
 * @property int $kapasitas
 * @property string $status
 * @property string|null $keterangan
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Fasilitas[] $fasilitas
 * @property Collection|Jadwal[] $jadwals
 *
 * @package App\Models
 */
class Bus extends Model
{
    protected $table = 'bus';

    protected $casts = [
        'kapasitas' => 'int'
    ];

    protected $fillable = [
        'nama',
        'plat_nomor',
        'kapasitas',
        'status',
        'keterangan'
    ];

    public function fasilitas()
    {
        return $this->belongsToMany(Fasilitas::class)
            ->withPivot('id')
            ->withTimestamps();
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function photos()
    {
        return $this->hasMany(BusPhoto::class);
    }

    public function kelasBus()
    {
        return $this->hasMany(KelasBus::class);
    }
}
