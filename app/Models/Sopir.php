<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Sopir
 *
 * @property int $id
 * @property int $user_id
 * @property string $nik
 * @property string $nomor_sim
 * @property string|null $alamat
 * @property string|null $telepon
 * @property Carbon|null $tanggal_lahir
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Users $users
 * @property Collection|Jadwal[] $jadwals
 *
 * @package App\Models
 */
class Sopir extends Model
{
    protected $table = "sopir";

    protected $casts = [
        "user_id" => "int",
        "tanggal_lahir" => "datetime",
    ];

    protected $fillable = ["user_id", "nik", "nomor_sim", "alamat", "telepon", "tanggal_lahir", "status"];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function conductorJadwals()
    {
        return $this->hasMany(Jadwal::class, "conductor_id");
    }
}
