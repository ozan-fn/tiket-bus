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
 * @property User $user
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
        'jadwal_kelas_bus_id',
        'kursi_id',
        'nik',
        'nama_penumpang',
        'tanggal_lahir',
        'jenis_kelamin',
        'nomor_telepon',
        'email',
        'kursi',
        'kode_tiket',
        'harga',
        'status',
        'waktu_pesan'
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function kursi()
    {
        return $this->belongsTo(Kursi::class);
    }

    public function jadwalKelasBus()
    {
        return $this->belongsTo(JadwalKelasBus::class, 'jadwal_kelas_bus_id');
    }
}
