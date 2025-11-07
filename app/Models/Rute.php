<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Rute
 * 
 * @property int $id
 * @property int $asal_terminal_id
 * @property int $tujuan_terminal_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Terminal[] $terminal
 * @property Collection|Jadwal[] $jadwals
 *
 * @package App\Models
 */
class Rute extends Model
{
	protected $table = 'rute';

	protected $casts = [
		'asal_terminal_id' => 'int',
		'tujuan_terminal_id' => 'int'
	];

	protected $fillable = [
		'asal_terminal_id',
		'tujuan_terminal_id'
	];

	public function terminal()
	{
		return $this->belongsToMany(Terminal::class)
					->withPivot('id', 'urutan')
					->withTimestamps();
	}

	public function jadwals()
	{
		return $this->hasMany(Jadwal::class);
	}
}
