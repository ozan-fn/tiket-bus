<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Pembayaran
 * 
 * @property int $id
 * @property int $user_id
 * @property int $tiket_id
 * @property string $metode
 * @property float $nominal
 * @property string $status
 * @property Carbon|null $waktu_bayar
 * @property string $kode_transaksi
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Tiket $tiket
 * @property Users $users
 *
 * @package App\Models
 */
class Pembayaran extends Model
{
	protected $table = 'pembayaran';

	protected $casts = [
		'user_id' => 'int',
		'tiket_id' => 'int',
		'nominal' => 'float',
		'waktu_bayar' => 'datetime'
	];

	protected $fillable = [
		'user_id',
		'tiket_id',
		'metode',
		'nominal',
		'status',
		'waktu_bayar',
		'kode_transaksi'
	];

	public function tiket()
	{
		return $this->belongsTo(Tiket::class);
	}

	public function users()
	{
		return $this->belongsTo(Users::class, 'user_id');
	}
}
