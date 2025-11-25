# API Documentation - Tiket Bus

## Base URL

```
http://localhost:8000/api
```

## Authentication

Menggunakan Laravel Sanctum dengan Bearer Token.

---

## 1. Authentication Endpoints

### Register

```http
POST /register
```

**Body:**

```json
{
    "name": "string",
    "email": "string",
    "password": "string"
}
```

**Response Success:**

```json
{
    "access_token": "1|abcdef123456...",
    "token_type": "Bearer",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "photo": null,
        "roles": ["passenger"],
        "role": "passenger"
    }
}
```

### Login

```http
POST /login
```

**Body:**

```json
{
    "email": "string",
    "password": "string"
}
```

**Response Success:**

```json
{
    "access_token": "string",
    "token_type": "Bearer",
    "user": {
        "id": 1,
        "name": "string",
        "email": "string",
        "photo": "http://localhost:8000/storage/profile/xxx.jpg",
        "roles": ["owner"],
        "role": "owner"
    }
}
```

**Keterangan:**

-   `photo`: URL lengkap foto profile (null jika belum upload)
-   `roles`: Array semua role user (bisa multiple roles)
-   `role`: Role utama (yang pertama), untuk logic sederhana di mobile app

### Logout (Auth Required)

```http
POST /logout
```

**Response Success:**

```json
{
    "message": "Logged out successfully"
}
```

---

## 2. Public Endpoints

### Bus

```http
GET /bus                    # List semua bus
GET /bus/{id}              # Detail bus
```

**Response GET /bus:**

```json
[
    {
        "id": 1,
        "nama": "Bus Sejahtera 001",
        "nomor_polisi": "B 1234 CD",
        "kapasitas": 40,
        "tahun_produksi": 2020,
        "created_at": "2025-11-24T10:00:00.000000Z",
        "updated_at": "2025-11-24T10:00:00.000000Z",
        "fasilitas": [
            {
                "id": 1,
                "nama": "AC",
                "icon": "ac"
            },
            {
                "id": 2,
                "nama": "WiFi",
                "icon": "wifi"
            }
        ],
        "kelas_bus": [
            {
                "id": 1,
                "bus_id": 1,
                "nama": "Ekonomi",
                "harga_dasar": 50000,
                "deskripsi": "Kelas ekonomi dengan fasilitas standar"
            }
        ],
        "photos": [
            {
                "id": 1,
                "bus_id": 1,
                "photo": "http://localhost:8000/storage/bus/photo1.jpg"
            }
        ]
    }
]
```

**Response GET /bus/{id}:**

```json
{
    "id": 1,
    "nama": "Bus Sejahtera 001",
    "nomor_polisi": "B 1234 CD",
    "kapasitas": 40,
    "tahun_produksi": 2020,
    "created_at": "2025-11-24T10:00:00.000000Z",
    "updated_at": "2025-11-24T10:00:00.000000Z",
    "fasilitas": [...],
    "kelas_bus": [...],
    "photos": [...]
}
```

### Terminal

```http
GET /terminal              # List semua terminal
GET /terminal/{id}         # Detail terminal
```

**Response GET /terminal:**

```json
[
    {
        "id": 1,
        "nama": "Terminal Pulogadung",
        "kota": "Jakarta",
        "alamat": "Jl. Pulogadung No. 1",
        "latitude": "-6.2088",
        "longitude": "106.8456",
        "created_at": "2025-11-24T10:00:00.000000Z",
        "updated_at": "2025-11-24T10:00:00.000000Z",
        "photos": [
            {
                "id": 1,
                "terminal_id": 1,
                "photo": "http://localhost:8000/storage/terminal/photo1.jpg"
            }
        ]
    }
]
```

### Rute

```http
GET /rute                  # List semua rute
GET /rute/{id}             # Detail rute
```

**Response GET /rute:**

```json
[
    {
        "id": 1,
        "nama": "Jakarta - Bandung",
        "created_at": "2025-11-24T10:00:00.000000Z",
        "updated_at": "2025-11-24T10:00:00.000000Z",
        "terminal_asal": {
            "id": 1,
            "nama": "Terminal Pulogadung",
            "kota": "Jakarta"
        },
        "terminal_tujuan": {
            "id": 2,
            "nama": "Terminal Leuwi Panjang",
            "kota": "Bandung"
        },
        "terminals": [
            {
                "id": 1,
                "nama": "Terminal Pulogadung",
                "urutan": 1
            },
            {
                "id": 2,
                "nama": "Terminal Leuwi Panjang",
                "urutan": 2
            }
        ]
    }
]
```

### Jadwal

```http
GET /jadwal?asal=&tujuan=&tanggal=    # List jadwal dengan filter
GET /jadwal/{id}                       # Detail jadwal
```

**Response GET /jadwal:**

```json
[
    {
        "id": 1,
        "rute_id": 1,
        "bus_id": 1,
        "sopir_id": 1,
        "tanggal": "2025-11-25",
        "waktu_berangkat": "08:00:00",
        "waktu_tiba": "12:00:00",
        "status": "aktif",
        "created_at": "2025-11-24T10:00:00.000000Z",
        "updated_at": "2025-11-24T10:00:00.000000Z",
        "rute": {
            "id": 1,
            "nama": "Jakarta - Bandung",
            "terminal_asal": {
                "id": 1,
                "nama": "Terminal Pulogadung",
                "kota": "Jakarta"
            },
            "terminal_tujuan": {
                "id": 2,
                "nama": "Terminal Leuwi Panjang",
                "kota": "Bandung"
            }
        },
        "bus": {
            "id": 1,
            "nama": "Bus Sejahtera 001",
            "nomor_polisi": "B 1234 CD",
            "kapasitas": 40
        },
        "sopir": {
            "id": 1,
            "nama": "Budi Santoso",
            "nomor_telepon": "081234567890"
        },
        "jadwal_kelas_bus": [
            {
                "id": 1,
                "jadwal_id": 1,
                "kelas_bus_id": 1,
                "harga": 75000,
                "kursi_tersedia": 38,
                "kelas_bus": {
                    "id": 1,
                    "nama": "Ekonomi",
                    "harga_dasar": 50000
                }
            }
        ]
    }
]
```

### Kursi

```http
GET /jadwal/{jadwal_id}/kursi                      # Layout kursi per jadwal
GET /kursi/{kursi_id}/check?jadwal_kelas_bus_id=  # Cek ketersediaan kursi
```

**Response GET /jadwal/{jadwal_id}/kursi:**

```json
[
    {
        "id": 1,
        "kelas_bus_id": 1,
        "nomor_kursi": "A1",
        "kelas_bus": {
            "id": 1,
            "nama": "Ekonomi"
        },
        "tersedia": true
    },
    {
        "id": 2,
        "kelas_bus_id": 1,
        "nomor_kursi": "A2",
        "kelas_bus": {
            "id": 1,
            "nama": "Ekonomi"
        },
        "tersedia": false
    }
]
```

**Response GET /kursi/{kursi_id}/check:**

```json
{
    "tersedia": true,
    "kursi": {
        "id": 1,
        "nomor_kursi": "A1",
        "kelas_bus": {
            "id": 1,
            "nama": "Ekonomi"
        }
    }
}
```

### Tiket

```http
GET /tiket/{kode_tiket}    # Cek tiket by kode
```

**Response GET /tiket/{kode_tiket}:**

```json
{
    "id": 1,
    "kode_tiket": "TKT-20251125-001",
    "user_id": 1,
    "jadwal_kelas_bus_id": 1,
    "kursi_id": 1,
    "nama_penumpang": "John Doe",
    "nik": "3201234567890123",
    "tanggal_lahir": "1990-01-01",
    "jenis_kelamin": "L",
    "nomor_telepon": "081234567890",
    "email": "john@example.com",
    "harga": 75000,
    "status": "terbayar",
    "created_at": "2025-11-25T10:00:00.000000Z",
    "updated_at": "2025-11-25T10:00:00.000000Z",
    "jadwal_kelas_bus": {
        "id": 1,
        "harga": 75000,
        "jadwal": {
            "id": 1,
            "tanggal": "2025-11-25",
            "waktu_berangkat": "08:00:00",
            "rute": {
                "id": 1,
                "nama": "Jakarta - Bandung"
            },
            "bus": {
                "id": 1,
                "nama": "Bus Sejahtera 001"
            }
        },
        "kelas_bus": {
            "id": 1,
            "nama": "Ekonomi"
        }
    },
    "kursi": {
        "id": 1,
        "nomor_kursi": "A1"
    },
    "pembayaran": {
        "id": 1,
        "status": "berhasil",
        "metode": "midtrans",
        "jumlah": 75000
    }
}
```

---

## 3. User Endpoints (Auth Required)

### Profile

```http
GET /user                  # Get profile
PUT /user                  # Update profile
PUT /user/password         # Update password
```

**Response GET /user:**

```json
{
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "photo": "http://localhost:8000/storage/profile/photo.jpg",
    "roles": ["passenger"],
    "role": "passenger",
    "created_at": "2025-11-24T10:00:00.000000Z",
    "updated_at": "2025-11-24T10:00:00.000000Z"
}
```

**Body PUT /user:**

```json
{
    "name": "John Doe Updated",
    "email": "john.new@example.com"
}
```

**Response PUT /user:**

```json
{
    "message": "Profile updated successfully",
    "user": {
        "id": 1,
        "name": "John Doe Updated",
        "email": "john.new@example.com",
        "photo": "http://localhost:8000/storage/profile/photo.jpg",
        "roles": ["passenger"],
        "role": "passenger"
    }
}
```

**Body PUT /user/password:**

```json
{
    "current_password": "oldpassword",
    "new_password": "newpassword",
    "new_password_confirmation": "newpassword"
}
```

**Response PUT /user/password:**

```json
{
    "message": "Password updated successfully."
}
```

### Tiket

```http
POST /tiket                # Buat pemesanan
GET /my-tickets            # Riwayat tiket user
```

**Body POST /tiket:**

```json
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

**Response POST /tiket:**

```json
{
    "id": 1,
    "kode_tiket": "TKT-20251125-001",
    "user_id": 1,
    "jadwal_kelas_bus_id": 1,
    "kursi_id": 1,
    "nama_penumpang": "John Doe",
    "nik": "3201234567890123",
    "tanggal_lahir": "1990-01-01",
    "jenis_kelamin": "L",
    "nomor_telepon": "081234567890",
    "email": "john@example.com",
    "harga": 75000,
    "status": "belum_bayar",
    "created_at": "2025-11-25T10:00:00.000000Z",
    "updated_at": "2025-11-25T10:00:00.000000Z"
}
```

**Response GET /my-tickets:**

```json
[
    {
        "id": 1,
        "kode_tiket": "TKT-20251125-001",
        "nama_penumpang": "John Doe",
        "harga": 75000,
        "status": "terbayar",
        "created_at": "2025-11-25T10:00:00.000000Z",
        "jadwal_kelas_bus": {
            "jadwal": {
                "tanggal": "2025-11-25",
                "waktu_berangkat": "08:00:00",
                "rute": {
                    "nama": "Jakarta - Bandung"
                },
                "bus": {
                    "nama": "Bus Sejahtera 001"
                }
            },
            "kelas_bus": {
                "nama": "Ekonomi"
            }
        },
        "kursi": {
            "nomor_kursi": "A1"
        }
    }
]
```

### Pembayaran

```http
POST /pembayaran           # Buat pembayaran
GET /pembayaran            # Riwayat pembayaran
GET /pembayaran/{id}       # Detail pembayaran
POST /pembayaran/callback  # Callback payment gateway
```

**Body POST /pembayaran:**

```json
{
    "tiket_id": 1,
    "metode": "midtrans"
}
```

**Response POST /pembayaran:**

```json
{
    "id": 1,
    "tiket_id": 1,
    "user_id": 1,
    "metode": "midtrans",
    "jumlah": 75000,
    "status": "pending",
    "snap_token": "abc123-midtrans-token",
    "created_at": "2025-11-25T10:00:00.000000Z",
    "updated_at": "2025-11-25T10:00:00.000000Z"
}
```

**Response GET /pembayaran:**

```json
[
    {
        "id": 1,
        "tiket_id": 1,
        "user_id": 1,
        "metode": "midtrans",
        "jumlah": 75000,
        "status": "berhasil",
        "bukti_pembayaran": "http://localhost:8000/storage/bukti-pembayaran/proof.jpg",
        "created_at": "2025-11-25T10:00:00.000000Z",
        "updated_at": "2025-11-25T10:00:00.000000Z",
        "tiket": {
            "id": 1,
            "kode_tiket": "TKT-20251125-001",
            "nama_penumpang": "John Doe"
        }
    }
]
```

**Response GET /pembayaran/{id}:**

```json
{
    "id": 1,
    "tiket_id": 1,
    "user_id": 1,
    "metode": "midtrans",
    "jumlah": 75000,
    "status": "berhasil",
    "bukti_pembayaran": "http://localhost:8000/storage/bukti-pembayaran/proof.jpg",
    "created_at": "2025-11-25T10:00:00.000000Z",
    "updated_at": "2025-11-25T10:00:00.000000Z",
    "tiket": {
        "id": 1,
        "kode_tiket": "TKT-20251125-001",
        "nama_penumpang": "John Doe",
        "jadwal_kelas_bus": {
            "jadwal": {
                "tanggal": "2025-11-25",
                "rute": {
                    "nama": "Jakarta - Bandung"
                }
            }
        }
    }
}
```

### Upload

```http
POST /upload/profile              # Upload foto profile user
POST /upload/bukti-pembayaran     # Upload bukti pembayaran
```

**Body POST /upload/profile:**

```
Content-Type: multipart/form-data
photo: file (jpg,png,gif, max 2MB)
```

**Body POST /upload/bukti-pembayaran:**

```
Content-Type: multipart/form-data
pembayaran_id: integer
bukti_pembayaran: file (jpg,png,pdf, max 5MB)
```

**Response POST /upload/profile:**

```json
{
    "message": "Profile photo uploaded successfully",
    "photo_url": "http://localhost:8000/storage/profile/photo123.jpg"
}
```

**Response POST /upload/bukti-pembayaran:**

```json
{
    "message": "Bukti pembayaran uploaded successfully",
    "bukti_url": "http://localhost:8000/storage/bukti-pembayaran/bukti123.jpg"
}
```

---

## 4. Admin/Agent Endpoints (Auth + Role Required)

### Bus Management

```http
POST /bus                  # Tambah bus
PUT /bus/{id}              # Update bus
DELETE /bus/{id}           # Hapus bus
```

**Body POST /bus:**

```json
{
    "nama": "Bus Sejahtera 002",
    "nomor_polisi": "B 5678 EF",
    "kapasitas": 40,
    "tahun_produksi": 2021,
    "fasilitas_ids": [1, 2, 3]
}
```

**Response POST /bus:**

```json
{
    "id": 2,
    "nama": "Bus Sejahtera 002",
    "nomor_polisi": "B 5678 EF",
    "kapasitas": 40,
    "tahun_produksi": 2021,
    "created_at": "2025-11-25T10:00:00.000000Z",
    "updated_at": "2025-11-25T10:00:00.000000Z"
}
```

**Response PUT /bus/{id}:**

```json
{
    "message": "Bus updated successfully",
    "bus": {
        "id": 2,
        "nama": "Bus Sejahtera 002 Updated",
        "nomor_polisi": "B 5678 EF",
        "kapasitas": 45,
        "tahun_produksi": 2021
    }
}
```

**Response DELETE /bus/{id}:**

```json
{
    "message": "Bus deleted successfully"
}
```

### Fasilitas Management

```http
GET /fasilitas             # List fasilitas
POST /fasilitas            # Tambah fasilitas
PUT /fasilitas/{id}        # Update fasilitas
DELETE /fasilitas/{id}     # Hapus fasilitas
```

### Terminal Management

```http
POST /terminal             # Tambah terminal
PUT /terminal/{id}         # Update terminal
DELETE /terminal/{id}      # Hapus terminal
```

### Rute Management

```http
POST /rute                 # Tambah rute
PUT /rute/{id}             # Update rute
DELETE /rute/{id}          # Hapus rute
```

### Sopir Management

```http
GET /sopir                 # List sopir
POST /sopir                # Tambah sopir
GET /sopir/{id}            # Detail sopir
PUT /sopir/{id}            # Update sopir
DELETE /sopir/{id}         # Hapus sopir
```

### Kelas Bus Management

```http
GET /kelas-bus             # List kelas bus
POST /kelas-bus            # Tambah kelas bus
GET /kelas-bus/{id}        # Detail kelas bus
PUT /kelas-bus/{id}        # Update kelas bus
DELETE /kelas-bus/{id}     # Hapus kelas bus
```

### Jadwal Management

```http
POST /jadwal               # Buat jadwal
PUT /jadwal/{id}           # Update jadwal
DELETE /jadwal/{id}        # Hapus jadwal
```

### Jadwal Kelas Bus Management

```http
GET /jadwal-kelas-bus      # List jadwal kelas bus
POST /jadwal-kelas-bus     # Buat jadwal kelas bus
GET /jadwal-kelas-bus/{id} # Detail
PUT /jadwal-kelas-bus/{id} # Update
DELETE /jadwal-kelas-bus/{id} # Hapus
```

### Laporan

```http
GET /laporan/tiket?dari=&sampai=&status=           # Laporan tiket
GET /laporan/pendapatan?dari=&sampai=&group_by=    # Laporan pendapatan
GET /laporan/penumpang?dari=&sampai=               # Laporan penumpang
```

**Response GET /laporan/tiket:**

```json
{
    "total": 150,
    "terbayar": 120,
    "belum_bayar": 20,
    "dibatalkan": 10,
    "data": [
        {
            "id": 1,
            "kode_tiket": "TKT-20251125-001",
            "nama_penumpang": "John Doe",
            "harga": 75000,
            "status": "terbayar",
            "created_at": "2025-11-25T10:00:00.000000Z"
        }
    ]
}
```

**Response GET /laporan/pendapatan:**

```json
{
    "total_pendapatan": 9000000,
    "total_transaksi": 120,
    "data": [
        {
            "tanggal": "2025-11-25",
            "total": 750000,
            "jumlah_tiket": 10
        },
        {
            "tanggal": "2025-11-24",
            "total": 600000,
            "jumlah_tiket": 8
        }
    ]
}
```

**Response GET /laporan/penumpang:**

```json
{
    "total_penumpang": 120,
    "data": [
        {
            "tanggal": "2025-11-25",
            "jumlah_penumpang": 10,
            "rute": [
                {
                    "nama": "Jakarta - Bandung",
                    "jumlah": 6
                },
                {
                    "nama": "Jakarta - Surabaya",
                    "jumlah": 4
                }
            ]
        }
    ]
}
```

### Upload (Admin/Agent)

```http
POST /upload/bus-photo           # Upload foto bus
DELETE /upload/bus-photo/{id}    # Hapus foto bus
POST /upload/terminal-photo      # Upload foto terminal
DELETE /upload/terminal-photo/{id} # Hapus foto terminal
```

**Body POST /upload/bus-photo:**

```
Content-Type: multipart/form-data
bus_id: integer
photo: file (jpg,png,gif, max 5MB)
```

**Body POST /upload/terminal-photo:**

```
Content-Type: multipart/form-data
terminal_id: integer
photo: file (jpg,png,gif, max 5MB)
```

**Response POST /upload/bus-photo:**

```json
{
    "message": "Bus photo uploaded successfully",
    "photo": {
        "id": 1,
        "bus_id": 1,
        "photo": "http://localhost:8000/storage/bus/photo123.jpg"
    }
}
```

**Response DELETE /upload/bus-photo/{id}:**

```json
{
    "message": "Bus photo deleted successfully"
}
```

**Response POST /upload/terminal-photo:**

```json
{
    "message": "Terminal photo uploaded successfully",
    "photo": {
        "id": 1,
        "terminal_id": 1,
        "photo": "http://localhost:8000/storage/terminal/photo123.jpg"
    }
}
```

**Response DELETE /upload/terminal-photo/{id}:**

```json
{
    "message": "Terminal photo deleted successfully"
}
```

---

## Response Format

### Success Response

```json
{
    "success": true,
    "data": {},
    "message": "optional message"
}
```

### Error Response

```json
{
    "success": false,
    "message": "Error message",
    "errors": {}
}
```

---

## Status Codes

-   `200` OK
-   `201` Created
-   `400` Bad Request
-   `401` Unauthorized
-   `403` Forbidden
-   `404` Not Found
-   `409` Conflict
-   `422` Validation Error
-   `500` Internal Server Error
