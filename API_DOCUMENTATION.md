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

### Logout (Auth Required)

```http
POST /logout
```

---

## 2. Public Endpoints

### Bus

```http
GET /bus                    # List semua bus
GET /bus/{id}              # Detail bus
```

### Terminal

```http
GET /terminal              # List semua terminal
GET /terminal/{id}         # Detail terminal
```

### Rute

```http
GET /rute                  # List semua rute
GET /rute/{id}             # Detail rute
```

### Jadwal

```http
GET /jadwal?asal=&tujuan=&tanggal=    # List jadwal dengan filter
GET /jadwal/{id}                       # Detail jadwal
```

### Kursi

```http
GET /jadwal/{jadwal_id}/kursi                      # Layout kursi per jadwal
GET /kursi/{kursi_id}/check?jadwal_kelas_bus_id=  # Cek ketersediaan kursi
```

### Tiket

```http
GET /tiket/{kode_tiket}    # Cek tiket by kode
```

---

## 3. User Endpoints (Auth Required)

### Profile

```http
GET /user                  # Get profile
PUT /user                  # Update profile
PUT /user/password         # Update password
```

### Tiket

```http
POST /tiket                # Buat pemesanan
GET /my-tickets            # Riwayat tiket user
```

**Body POST /tiket:**

```json
{
    "jadwal_kelas_bus_id": "integer",
    "kursi_id": "integer",
    "nama_penumpang": "string",
    "nik": "string",
    "tanggal_lahir": "date",
    "jenis_kelamin": "L|P",
    "nomor_telepon": "string",
    "email": "string"
}
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
    "tiket_id": "integer",
    "metode": "midtrans|transfer|tunai"
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
