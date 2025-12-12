<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusPhoto extends Model
{
    protected $table = "bus_photos";

    protected $fillable = ["bus_id", "path"];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
}
