<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Jadwal
 *
 * @property int $id
 * @property int $bus_id
 * @property int $sopir_id
 * @property int $rute_id
 * @property Carbon $tanggal_berangkat
 * @property Carbon $jam_berangkat
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Bus $bus
 * @property Rute $rute
 * @property Sopir $sopir
 * @property Sopir $conductor
 * @property Collection|Tiket[] $tikets
 *
 * @package App\Models
 */
class Jadwal extends Model
{
    protected $table = "jadwal";

    protected $casts = [
        "bus_id" => "int",
        "sopir_id" => "int",
        "rute_id" => "int",
        "tanggal_berangkat" => "datetime",
        "jam_berangkat" => "datetime",
    ];

    protected $fillable = ["bus_id", "sopir_id", "conductor_id", "rute_id", "tanggal_berangkat", "jam_berangkat", "status"];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function rute()
    {
        return $this->belongsTo(Rute::class);
    }

    public function sopir()
    {
        return $this->belongsTo(Sopir::class);
    }

    public function conductor()
    {
        return $this->belongsTo(Sopir::class, "conductor_id");
    }

    public function jadwalKelasBus()
    {
        return $this->hasMany(JadwalKelasBus::class);
    }

    public function scopeActive($query)
    {
        return $query->where("status", "aktif")
            ->where(function ($q) {
                $now = now();
                $q->where("tanggal_berangkat", ">", $now->toDateString())
                    ->orWhere(function ($q2) use ($now) {
                        $q2->where("tanggal_berangkat", $now->toDateString())
                            ->where("jam_berangkat", ">=", $now->toTimeString());
                    });
            });
    }

    public function scopeExpired($query)
    {
        return $query->where(function ($q) {
            $now = now();
            $q->where("tanggal_berangkat", "<", $now->toDateString())
                ->orWhere(function ($q2) use ($now) {
                    $q2->whereDate("tanggal_berangkat", $now->toDateString())
                        ->whereTime("jam_berangkat", "<", $now->toTimeString());
                });
        });
    }
}
