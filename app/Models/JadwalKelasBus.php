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

    /**
     * Get the related KelasBus through the BusKelasBus pivot model.
     *
     * We use hasOneThrough with the following key mapping:
     * - The local key on this model that references the through model: `bus_kelas_bus_id`
     * - The local key on the through model that references the final model: `kelas_bus_id`
     *
     * Note: Eloquent's hasOneThrough expects the third parameter to be the foreign key on the
     * through model that references this model. In our schema the parent (JadwalKelasBus)
     * holds the foreign key to BusKelasBus, so we provide the through-local key and adjust
     * the other parameters accordingly.
     */
    public function kelasBus()
    {
        // Parameters:
        // 1: Final related model
        // 2: Through model
        // 3: Foreign key on the through model that references THIS model (not present in our schema),
        //    so use the through model's primary key 'id' and set local key to our 'bus_kelas_bus_id'.
        // 4: Foreign key on the final model that references the through model (in our schema the through
        //    model holds the foreign key to final model), so we use the through model's foreign key 'kelas_bus_id'
        //    and final model's primary key 'id' as the secondLocalKey.
        // 5: Local key on THIS model (bus_kelas_bus_id)
        // 6: Local key on the through model that references the final model (kelas_bus_id)
        return $this->hasOneThrough(
            KelasBus::class,
            BusKelasBus::class,
            "id", // through primary key (used because through does not reference parent)
            "id", // final primary key (will be matched against through.kelas_bus_id)
            "bus_kelas_bus_id", // local key on this model
            "kelas_bus_id", // local key on through model pointing to final model
        );
    }

    public function bus()
    {
        return $this->belongsTo(KelasBus::class);
    }

    public function tikets()
    {
        return $this->hasMany(Tiket::class);
    }
}
