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
 * @property Collection|Tiket[] $tikets
 *
 * @package App\Models
 */
class Jadwal extends Model
{
	protected $table = 'jadwal';

	protected $casts = [
		'bus_id' => 'int',
		'sopir_id' => 'int',
		'rute_id' => 'int',
		'tanggal_berangkat' => 'datetime',
		'jam_berangkat' => 'datetime'
	];

	protected $fillable = [
		'bus_id',
		'sopir_id',
		'rute_id',
		'tanggal_berangkat',
		'jam_berangkat',
		'status'
	];

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

	public function tikets()
	{
		return $this->hasMany(Tiket::class);
	}
}
