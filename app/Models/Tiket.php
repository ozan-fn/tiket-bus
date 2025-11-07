<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tiket
 * 
 * @property int $id
 * @property int $user_id
 * @property int $jadwal_id
 * @property string $nik
 * @property string $nama_penumpang
 * @property Carbon|null $tanggal_lahir
 * @property string $jenis_kelamin
 * @property string $kode_tiket
 * @property float $harga
 * @property string $status
 * @property Carbon $waktu_pesan
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Jadwal $jadwal
 * @property Users $users
 * @property Collection|Pembayaran[] $pembayarans
 *
 * @package App\Models
 */
class Tiket extends Model
{
	protected $table = 'tiket';

	protected $casts = [
		'user_id' => 'int',
		'jadwal_id' => 'int',
		'tanggal_lahir' => 'datetime',
		'harga' => 'float',
		'waktu_pesan' => 'datetime'
	];

	protected $fillable = [
		'user_id',
		'jadwal_id',
		'nik',
		'nama_penumpang',
		'tanggal_lahir',
		'jenis_kelamin',
		'kode_tiket',
		'harga',
		'status',
		'waktu_pesan'
	];

	public function jadwal()
	{
		return $this->belongsTo(Jadwal::class);
	}

	public function users()
	{
		return $this->belongsTo(Users::class, 'user_id');
	}

	public function pembayarans()
	{
		return $this->hasMany(Pembayaran::class);
	}
}
