<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RuteTerminal
 * 
 * @property int $id
 * @property int $rute_id
 * @property int $terminal_id
 * @property int|null $urutan
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Rute $rute
 * @property Terminal $terminal
 *
 * @package App\Models
 */
class RuteTerminal extends Model
{
	protected $table = 'rute_terminal';

	protected $casts = [
		'rute_id' => 'int',
		'terminal_id' => 'int',
		'urutan' => 'int'
	];

	protected $fillable = [
		'rute_id',
		'terminal_id',
		'urutan'
	];

	public function rute()
	{
		return $this->belongsTo(Rute::class);
	}

	public function terminal()
	{
		return $this->belongsTo(Terminal::class);
	}
}
