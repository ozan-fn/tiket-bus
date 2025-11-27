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
 * @property User $user
 *
 * @package App\Models
 */
class Pembayaran extends Model
{
    protected $table = "pembayaran";

    protected $casts = [
        "user_id" => "int",
        "tiket_id" => "int",
        "nominal" => "float",
        "waktu_bayar" => "datetime",
    ];

    protected $fillable = ["user_id", "tiket_id", "metode", "nominal", "status", "waktu_bayar", "kode_transaksi", "bukti_pembayaran", "external_id"];

    public function tiket()
    {
        return $this->belongsTo(Tiket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
