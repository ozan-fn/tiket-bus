<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BusFasilitas
 * 
 * @property int $id
 * @property int $bus_id
 * @property int $fasilitas_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Bus $bus
 * @property Fasilitas $fasilitas
 *
 * @package App\Models
 */
class BusFasilitas extends Model
{
	protected $table = 'bus_fasilitas';

	protected $casts = [
		'bus_id' => 'int',
		'fasilitas_id' => 'int'
	];

	protected $fillable = [
		'bus_id',
		'fasilitas_id'
	];

	public function bus()
	{
		return $this->belongsTo(Bus::class);
	}

	public function fasilitas()
	{
		return $this->belongsTo(Fasilitas::class);
	}
}
