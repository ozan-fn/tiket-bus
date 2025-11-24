<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Fasilitas
 * 
 * @property int $id
 * @property string $nama
 * @property string|null $deskripsi
 * @property string|null $icon
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Bus[] $bus
 *
 * @package App\Models
 */
class Fasilitas extends Model
{
    protected $table = 'fasilitas';

    protected $fillable = [
        'nama',
        'deskripsi',
        'icon'
    ];

    public function bus()
    {
        return $this->belongsToMany(Bus::class)
            ->withPivot('id')
            ->withTimestamps();
    }
}
