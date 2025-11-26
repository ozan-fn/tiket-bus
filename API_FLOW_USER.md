# REST API Flow - Pemesanan Tiket Bus

## Flow Pemesanan User (Customer Journey)

```
1. Pilih Rute → 2. Lihat Jadwal & Bus → 3. Pilih Kelas → 4. Pilih Kursi → 5. Booking → 6. Bayar → 7. E-Ticket
```

---

## 1️⃣ Pilih Rute (Asal & Tujuan)

### Endpoint: `GET /api/jadwal`

**Query Parameters:**

```
?asal=Jakarta&tujuan=Bandung&tanggal=2025-01-15&page=1&per_page=10
```

**Response:**

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "tanggal_berangkat": "2025-01-15",
            "jam_berangkat": "08:00:00",
            "status": "tersedia",
            "bus": {
                "id": 1,
                "nama": "Sinar Jaya Executive",
                "plat_nomor": "B 1234 ABC",
                "kapasitas": 40
            },
            "rute": {
                "id": 1,
                "asal": "Terminal Kampung Rambutan",
                "tujuan": "Terminal Leuwi Panjang"
            },
            "kelas_tersedia": [
                {
                    "id": 1,
                    "kelas_bus_id": 1,
                    "nama_kelas": "Executive",
                    "harga": 150000,
                    "kursi_tersedia": 30,
                    "total_kursi": 40
                },
                {
                    "id": 2,
                    "kelas_bus_id": 2,
                    "nama_kelas": "VIP",
                    "harga": 200000,
                    "kursi_tersedia": 15,
                    "total_kursi": 20
                }
            ]
        }
    ],
    "pagination": {
        "total": 1,
        "per_page": 10,
        "current_page": 1,
        "last_page": 1,
        "from": 1,
        "to": 1
    }
}
```

---

## 2️⃣ Lihat Detail Jadwal (Opsional)

### Endpoint: `GET /api/jadwal/{id}`

**Response:**

```json
{
    "success": true,
    "data": {
        "id": 1,
        "tanggal_berangkat": "2025-01-15",
        "jam_berangkat": "08:00:00",
        "status": "tersedia",
        "bus": {
            "id": 1,
            "nama": "Sinar Jaya Executive",
            "plat_nomor": "B 1234 ABC",
            "kapasitas": 40
        },
        "sopir": {
            "id": 1,
            "nama": "Budi Santoso"
        },
        "rute": {
            "id": 1,
            "asal": {
                "id": 1,
                "nama": "Terminal Kampung Rambutan",
                "kota": "Jakarta"
            },
            "tujuan": {
                "id": 2,
                "nama": "Terminal Leuwi Panjang",
                "kota": "Bandung"
            }
        },
        "kelas_tersedia": [
            {
                "id": 1,
                "kelas_bus_id": 1,
                "nama_kelas": "Executive",
                "deskripsi": "Kursi nyaman dengan AC",
                "jumlah_kursi": 40,
                "harga": 150000,
                "kursi_tersedia": 30
            }
        ]
    }
}
```

---

## 3️⃣ Lihat Denah Kursi (Seat Map)

### Endpoint: `GET /api/jadwal/{jadwal_id}/kursi`

**Query Parameters:**

```
?jadwal_kelas_bus_id=1
```

**Response:**

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "nomor_kursi": "A1",
            "posisi": "kiri",
            "baris": 1,
            "tersedia": true
        },
        {
            "id": 2,
            "nomor_kursi": "A2",
            "posisi": "kanan",
            "baris": 1,
            "tersedia": false
        }
    ]
}
```

---

## 4️⃣ Cek Ketersediaan Kursi Spesifik (Opsional)

### Endpoint: `GET /api/kursi/{kursi_id}/check`

**Query Parameters:**

```
?jadwal_kelas_bus_id=1
```

**Response:**

```json
{
    "success": true,
    "data": {
        "kursi_id": 1,
        "nomor_kursi": "A1",
        "tersedia": true,
        "jadwal_kelas_bus_id": 1
    }
}
```

---

## 5️⃣ Booking Tiket (Lock Kursi)

### Endpoint: `POST /api/tiket`

**Headers:**

```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**

````json
{
    "jadwal_kelas_bus_id": 1,
    "kursi_id": 1,
    "nama_penumpang": "John Doe",
    "nik": "3201234567890123",
    "tanggal_lahir": "1990-01-01",
    "jenis_kelamin": "L",
    "nomor_telepon": "081234567890",
    "email": "john@example.com"
}
```

**Response:**

```json
{
    "success": true,
    "message": "Tiket berhasil dipesan",
    "data": {
        "id": 1,
        "kode_tiket": "TKT-20250115-0001",
        "user_id": 1,
        "jadwal_kelas_bus_id": 1,
        "kursi_id": 1,
        "nama_penumpang": "John Doe",
        "no_identitas": "3201234567890123",
        "no_telepon": "081234567890",
        "email": "john@example.com",
        "status": "dipesan",
        "created_at": "2025-01-15T08:00:00.000000Z"
    }
}
````

> **⏰ TTL Booking:** Tiket dengan status `dipesan` akan otomatis dirilis jika tidak dibayar dalam 30 menit.

---

## 6️⃣ Bayar Tiket

### Endpoint: `POST /api/pembayaran`

**Headers:**

```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**

#### A. Pembayaran via Xendit (E-Wallet/Virtual Account)

```json
{
    "tiket_id": 1,
    "metode": "xendit",
    "nominal": 150000,
    "success_redirect_url": "https://myapp.com/payment-success",
    "failure_redirect_url": "https://myapp.com/payment-failed"
}
```

**Response:**

```json
{
    "success": true,
    "message": "Pembayaran berhasil dibuat",
    "data": {
        "id": 1,
        "kode_transaksi": "PAY-20250115-0001",
        "external_id": "inv_123abc",
        "tiket_id": 1,
        "metode": "xendit",
        "nominal": 150000,
        "status": "pending",
        "waktu_bayar": null,
        "invoice_url": "https://checkout.xendit.co/v2/123abc",
        "created_at": "2025-01-15T08:05:00.000000Z"
    }
}
```

> **🔗 Next:** Redirect user ke `invoice_url` untuk melakukan pembayaran.

#### B. Pembayaran Manual (Transfer/Tunai)

```json
{
    "tiket_id": 1,
    "metode": "transfer",
    "nominal": 150000,
    "bukti_pembayaran": "storage/bukti/bukti-20250115.jpg"
}
```

**Response:**

```json
{
    "success": true,
    "message": "Pembayaran berhasil dibuat, menunggu approval admin",
    "data": {
        "id": 2,
        "kode_transaksi": "PAY-20250115-0002",
        "tiket_id": 1,
        "metode": "transfer",
        "nominal": 150000,
        "status": "pending",
        "bukti_pembayaran": "storage/bukti/bukti-20250115.jpg",
        "created_at": "2025-01-15T08:05:00.000000Z"
    }
}
```

> **👨‍💼 Admin Approval:** Admin/Agent akan approve/reject pembayaran manual.

---

## 7️⃣ Webhook Callback (Payment Gateway)

### Endpoint: `POST /api/pembayaran/callback`

**Headers:**

```
x-callback-token: {XENDIT_CALLBACK_TOKEN}
Content-Type: application/json
```

**Request Body (dari Xendit):**

```json
{
    "id": "inv_123abc",
    "external_id": "PAY-20250115-0001",
    "status": "PAID",
    "paid_at": "2025-01-15T08:10:00.000Z",
    "amount": 150000
}
```

**Response:**

```json
{
    "success": true,
    "message": "Callback berhasil diproses"
}
```

> **✅ Otomatis:** Status tiket berubah dari `dipesan` → `dibayar`

---

## 8️⃣ Lihat E-Ticket

### Endpoint: `GET /api/tiket/{kode_tiket}`

**Public endpoint** - tidak perlu auth jika pakai kode tiket.

**Response:**

```json
{
    "success": true,
    "data": {
        "id": 1,
        "kode_tiket": "TKT-20250115-0001",
        "nama_penumpang": "John Doe",
        "no_identitas": "3201234567890123",
        "no_telepon": "081234567890",
        "email": "john@example.com",
        "status": "dibayar",
        "jadwal": {
            "id": 1,
            "tanggal_berangkat": "2025-01-15",
            "jam_berangkat": "08:00:00",
            "bus": {
                "nama": "Sinar Jaya Executive",
                "plat_nomor": "B 1234 ABC"
            },
            "rute": {
                "asal": "Terminal Kampung Rambutan",
                "tujuan": "Terminal Leuwi Panjang"
            }
        },
        "kursi": {
            "nomor_kursi": "A1"
        },
        "kelas": {
            "nama_kelas": "Executive"
        },
        "pembayaran": {
            "kode_transaksi": "PAY-20250115-0001",
            "metode": "xendit",
            "nominal": 150000,
            "status": "berhasil",
            "waktu_bayar": "2025-01-15T08:10:00.000000Z"
        }
    }
}
```

---

## 9️⃣ Riwayat Tiket User

### Endpoint: `GET /api/my-tickets`

**Headers:**

```
Authorization: Bearer {token}
```

**Response:**

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "kode_tiket": "TKT-20250115-0001",
            "status": "dibayar",
            "tanggal_berangkat": "2025-01-15",
            "jam_berangkat": "08:00:00",
            "rute": "Jakarta → Bandung",
            "nomor_kursi": "A1"
        }
    ]
}
```

---

## 🔟 Verifikasi Tiket (Petugas)

### A. Cek Validitas Tiket

**Endpoint:** `POST /api/tiket/{kode_tiket}/verify`

**Headers:**

```
Authorization: Bearer {token}
```

**Use Case:** Petugas scan QR code atau input kode tiket manual untuk verifikasi

**Response (Valid):**

```json
{
    "success": true,
    "message": "Tiket valid",
    "data": {
        "kode_tiket": "TKT-20250115-0001",
        "status": "dibayar",
        "penumpang": {
            "nama": "John Doe",
            "nik": "3201234567890123",
            "jenis_kelamin": "Laki-laki",
            "nomor_telepon": "081234567890"
        },
        "jadwal": {
            "tanggal_berangkat": "2025-01-15",
            "jam_berangkat": "08:00:00"
        },
        "bus": {
            "nama": "Sinar Jaya Executive",
            "plat_nomor": "B 1234 ABC"
        },
        "rute": {
            "asal": "Terminal Kampung Rambutan",
            "tujuan": "Terminal Leuwi Panjang"
        },
        "kelas": "Executive",
        "kursi": {
            "nomor": "A1",
            "posisi": "kiri"
        },
        "harga": 150000,
        "pembayaran": {
            "metode": "xendit",
            "waktu_bayar": "2025-01-15T08:10:00.000000Z"
        }
    }
}
```

**Response (Belum Bayar):**

```json
{
    "success": false,
    "message": "Tiket belum dibayar atau sudah dibatalkan",
    "data": {
        "kode_tiket": "TKT-20250115-0001",
        "status": "dipesan"
    }
}
```

**Response (Sudah Digunakan):**

```json
{
    "success": false,
    "message": "Tiket sudah pernah digunakan",
    "data": {
        "kode_tiket": "TKT-20250115-0001",
        "status": "selesai",
        "waktu_digunakan": "2025-01-15T07:45:00.000000Z"
    }
}
```

### B. Check-in Penumpang

**Endpoint:** `POST /api/tiket/{kode_tiket}/checkin`

**Headers:**

```
Authorization: Bearer {token}
```

**Use Case:** Setelah verifikasi valid, petugas klik tombol "Check-in" untuk tandai penumpang naik

**Response:**

```json
{
    "success": true,
    "message": "Check-in berhasil, penumpang boleh naik",
    "data": {
        "kode_tiket": "TKT-20250115-0001",
        "nama_penumpang": "John Doe",
        "status": "selesai",
        "waktu_checkin": "2025-01-15T07:45:00.000000Z"
    }
}
```

---

## 1️⃣1️⃣ Cek Status Pembayaran (Manual Sync)

### Endpoint: `GET /api/pembayaran/{id}/check-status`

**Headers:**

```
Authorization: Bearer {token}
```

**Use Case:** Jika webhook gagal/lambat, user bisa manual refresh status pembayaran

**Response:**

```json
{
    "success": true,
    "message": "Status berhasil disinkronkan: PAID",
    "data": {
        "id": 1,
        "kode_transaksi": "TRX-WRG3FLUM8SHZ",
        "status": "berhasil",
        "xendit_status": "PAID",
        "waktu_bayar": "2025-11-25T15:23:36.000000Z"
    }
}
```

---

## Status Flow

### Status Tiket

-   `dipesan` → Booking berhasil, belum bayar (TTL: 30 menit)
-   `dibayar` → Pembayaran berhasil, siap berangkat
-   `selesai` → Penumpang sudah check-in dan naik bus
-   `batal` → Booking dibatalkan (manual atau TTL expired)

### Status Pembayaran

-   `pending` → Menunggu pembayaran
-   `berhasil` → Pembayaran berhasil (dari webhook atau admin approval)
-   `gagal` → Pembayaran gagal/ditolak
-   `refund` → Pembayaran di-refund

---

## Endpoints Tambahan (Admin/Agent)

### Approve Pembayaran Manual

```
POST /api/pembayaran/{id}/approve
Authorization: Bearer {token} (role: owner|agent)
```

### Reject Pembayaran Manual

```
POST /api/pembayaran/{id}/reject
Authorization: Bearer {token} (role: owner|agent)
```

---

## Notes

1. **Idempotensi:** Pembayaran duplikat untuk `tiket_id` yang sama akan ditolak
2. **Webhook Security:** Callback harus menyertakan `x-callback-token` yang valid
3. **TTL Auto-release:** Gunakan Laravel Scheduler (`php artisan schedule:work`) untuk auto-release booking expired
4. **Mobile Deep Link:** Pass `success_redirect_url` dan `failure_redirect_url` untuk redirect ke app mobile setelah pembayaran

---

## Environment Variables Required

```env
XENDIT_API_KEY=xnd_production_xxxxx
XENDIT_BASE_URL=https://api.xendit.co
XENDIT_CALLBACK_TOKEN=your_secret_callback_token
```
