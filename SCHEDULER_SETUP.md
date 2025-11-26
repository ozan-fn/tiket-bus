# Laravel Scheduler Setup - Auto-release Expired Bookings

## Fitur

Scheduler ini akan otomatis membatalkan tiket yang statusnya `dipesan` (booking) tapi belum dibayar dalam 30 menit.

## Cara Menjalankan

### Development (Terminal tetap buka)

```bash
php artisan schedule:work
```

Output:

```
[2025-01-15 08:00:00] Running scheduled command: release-expired-bookings
[2025-01-15 08:01:00] Running scheduled command: release-expired-bookings
Released 3 expired bookings
```

### Production (Server Linux)

Tambahkan ke **crontab**:

```bash
crontab -e
```

Tambahkan line ini:

```
* * * * * cd /path/to/tiket-bus && php artisan schedule:run >> /dev/null 2>&1
```

**Windows Server** (Task Scheduler):

1. Buka **Task Scheduler**
2. Create Basic Task → Name: "Laravel Scheduler"
3. Trigger: Daily, repeat every 1 minute
4. Action: Start a program
    - Program: `C:\xampp\php\php.exe`
    - Arguments: `artisan schedule:run`
    - Start in: `D:\Project\Magang\tiket-bus`

---

## Kode Scheduler

File: `routes/console.php`

```php
Schedule::call(function () {
    $releasedCount = \App\Models\Tiket::where('status', 'dipesan')
        ->where('created_at', '<', now()->subMinutes(30))
        ->update(['status' => 'batal']);

    if ($releasedCount > 0) {
        \Illuminate\Support\Facades\Log::info("Released {$releasedCount} expired bookings");
    }
})->everyMinute()->name('release-expired-bookings');
```

---

## Testing Scheduler

### Manual Test

```bash
php artisan schedule:test
```

### Check Logs

```bash
tail -f storage/logs/laravel.log
```

---

## Monitoring

### List Scheduled Tasks

```bash
php artisan schedule:list
```

Output:

```
0 * * * * release-expired-bookings .... Next Due: 1 minute from now
```

### Run Single Task Manually

```bash
php artisan schedule:run
```

---

## TTL Booking Rules

| Status    | TTL       | Action                      |
| --------- | --------- | --------------------------- |
| `dipesan` | 30 menit  | Auto batal jika belum bayar |
| `dibayar` | Permanent | Tidak ada TTL               |
| `batal`   | Permanent | Tidak bisa direstore        |
| `selesai` | Permanent | Setelah jadwal selesai      |

---

## Alternative: Manual Cron (Tanpa Laravel Scheduler)

Jika tidak ingin pakai Laravel Scheduler, bisa langsung buat MySQL EVENT:

```sql
-- Butuh SUPER privilege
SET GLOBAL event_scheduler = ON;

CREATE EVENT release_expired_bookings
ON SCHEDULE EVERY 1 MINUTE
DO
  UPDATE tiket
  SET status = 'batal'
  WHERE status = 'dipesan'
  AND created_at < NOW() - INTERVAL 30 MINUTE;
```

**⚠️ Catatan:** MySQL EVENT butuh privilege `EVENT` dan `event_scheduler=ON`.

---

## Best Practice

1. **Development:** Gunakan `php artisan schedule:work`
2. **Production:** Gunakan crontab/Task Scheduler
3. **Monitoring:** Cek logs di `storage/logs/laravel.log`
4. **Alert:** Setup notification jika banyak booking expired (misal >50/hari)

---

## Troubleshooting

### Scheduler tidak jalan

```bash
# Cek apakah artisan bisa dijalankan
php artisan list

# Cek permission
chmod -R 775 storage bootstrap/cache

# Cek crontab (Linux)
crontab -l

# Test manual
php artisan schedule:run
```

### Windows: "php not found"

Pastikan PHP sudah di PATH:

```cmd
set PATH=%PATH%;C:\xampp\php
php -v
```

---

## Conclusion

✅ Scheduler sudah dikonfigurasi  
✅ Auto-release booking expired setiap 1 menit  
✅ Logging untuk monitoring

**Next:** Jalankan `php artisan schedule:work` untuk testing!
