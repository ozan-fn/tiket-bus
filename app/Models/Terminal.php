<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Terminal
 * 
 * @property int $id
 * @property string $nama_terminal
 * @property string $nama_kota
 * @property string|null $alamat
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Rute[] $rutes
 * @property Collection|Rute[] $rute
 *
 * @package App\Models
 */
class Terminal extends Model
{
    protected $table = 'terminal';

    protected $fillable = [
        'nama_terminal',
        'nama_kota',
        'alamat'
    ];

    public function rutes()
    {
        return $this->hasMany(Rute::class, 'tujuan_terminal_id');
    }

    public function rute()
    {
        return $this->belongsToMany(Rute::class)
            ->withPivot('id', 'urutan')
            ->withTimestamps();
    }

    public function photos()
    {
        return $this->hasMany(TerminalPhoto::class);
    }
}
