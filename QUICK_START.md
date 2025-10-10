# 🚀 Quick Start Guide - S&Waldorf Sales Application

## Langkah Cepat Setup (5 Menit)

### 1️⃣ Install Dependencies
```bash
composer install
```

### 2️⃣ Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 3️⃣ Konfigurasi Database
Edit file `.env`:
```env
DB_DATABASE=swaldorf_db
DB_USERNAME=root
DB_PASSWORD=
```

### 4️⃣ Buat Database
Buka MySQL/phpMyAdmin dan jalankan:
```sql
CREATE DATABASE swaldorf_db;
```

### 5️⃣ Migrate & Seed
```bash
php artisan migrate
php artisan db:seed --class=SWaldorfSeeder
```

### 6️⃣ Jalankan Server
```bash
php artisan serve
```

### 7️⃣ Akses Aplikasi
Buka browser: **http://localhost:8000**

---

## 🔑 Login Credentials

- **Email**: admin@swaldorf.com
- **Password**: password

---

## ✅ Fitur yang Sudah Dibuat

### ✓ Dashboard Inventori
- Statistik: User, Kategori, Produk, Transaksi
- Total Pendapatan
- Alert Stok Rendah
- Tabel Status Stok
- Riwayat Penjualan

### ✓ Sistem Perhitungan Diskon
- Validasi diskon max 100%
- Formula: `total = harga - (harga * diskon/100)`
- Perhitungan otomatis
- Tampilan nilai diskon

### ✓ Transaksi Penjualan
- Form transaksi lengkap
- Multiple product selection
- Integrasi promo/diskon
- Validasi stok
- Perhitungan kembalian
- Detail transaksi
- Print struk

### ✓ Database & Models
- 7 Tabel dengan relasi lengkap
- Eloquent relationships
- Migrations & Seeders
- Sample data (12 produk, 4 promo)

---

## 🧪 Test Aplikasi

### Test 1: Lihat Dashboard
1. Akses http://localhost:8000
2. Lihat statistik dan data

### Test 2: Buat Transaksi dengan Diskon
1. Klik "Transaksi Baru" (atau akses /penjualan/create)
2. Pilih produk: Dress Elegant Black
3. Pilih promo: GRAND10 (10%)
4. Lihat perhitungan diskon otomatis
5. Input jumlah bayar: 500000
6. Submit transaksi

### Test 3: Lihat Detail Transaksi
1. Klik "Detail" pada transaksi
2. Lihat informasi lengkap dengan diskon

---

## 📊 Data Sample

### Promo Aktif
- **GRAND10** → 10% off
- **FLASH20** → 20% off
- **MEMBER15** → 15% off
- **MEGA50** → 50% off

### Produk Sample
- Dress Elegant Black - Rp 450.000
- Kemeja Formal - Rp 350.000
- Blazer Slim Fit - Rp 750.000
- Kalung Emas 18K - Rp 1.200.000
- Dan 8 produk lainnya...

---

## 🔧 Troubleshooting

### Error: "SQLSTATE[HY000] [1049]"
**Solusi**: Database belum dibuat
```sql
CREATE DATABASE swaldorf_db;
```

### Error: "Class SWaldorfSeeder not found"
**Solusi**: 
```bash
composer dump-autoload
php artisan db:seed --class=SWaldorfSeeder
```

### Error: "419 Page Expired"
**Solusi**: Clear cache
```bash
php artisan cache:clear
php artisan config:clear
```

---

## 📝 Struktur File Penting

```
SWaldorf/
├── app/
│   ├── Http/Controllers/
│   │   ├── DashboardController.php    ← Dashboard
│   │   └── PenjualanController.php    ← Transaksi & Diskon
│   └── Models/
│       ├── Produk.php                 ← Model Produk
│       ├── Penjualan.php              ← Model Penjualan
│       ├── DetailPenjualan.php        ← Model Detail
│       └── Promo.php                  ← Model Promo
├── database/
│   ├── migrations/                    ← Struktur tabel
│   └── seeders/
│       └── SWaldorfSeeder.php         ← Data sample
├── resources/views/
│   └── home/
│       ├── dashboard.blade.php        ← Dashboard view
│       └── penjualan/
│           ├── create.blade.php       ← Form transaksi
│           ├── index.blade.php        ← List transaksi
│           └── show.blade.php         ← Detail transaksi
└── routes/
    └── web.php                        ← Routing
```

---

## 🎯 Fitur Utama Sesuai Tugas

### ✅ Analisis Kebutuhan
- Entitas: Penjualan, Pelanggan, Produk, Detail Penjualan
- Informasi lengkap tersimpan di database

### ✅ Perancangan Skema
- ERD lengkap (lihat DOKUMENTASI_SWALDORF.md)
- 7 tabel dengan relasi proper

### ✅ Pembuatan Tabel
- Migrations untuk semua entitas
- Primary Key & Foreign Key defined

### ✅ Definisi Relasi
- Eloquent relationships implemented
- Cascade delete & set null

### ✅ Input Data
- Seeder dengan 12 produk, 4 promo, 4 pelanggan
- 3 transaksi sample

### ✅ Pengujian Query
- Dashboard menampilkan data real-time
- Pencarian & filtering

### ✅ Optimasi
- Eager loading untuk relasi
- Index pada foreign keys

### ✅ Dokumentasi
- README lengkap
- Quick start guide
- Inline comments

### ✅ Form Input Diskon
- Form transaksi dengan promo selection
- Validasi diskon ≤ 100%

### ✅ Validasi Input
- Server-side & client-side validation
- Harga > 0, Diskon 0-100%

### ✅ Hitung Total Harga
- Formula: `total = harga - (harga * diskon/100)`
- Implemented di PenjualanController

### ✅ Tampilkan Hasil
- Nilai diskon ditampilkan
- Total setelah diskon ditampilkan
- Kembalian dihitung otomatis

### ✅ Uji Aplikasi
- Test scenarios documented
- Sample data untuk testing

### ✅ Selesai & Simpan
- Kode tersimpan & ready to run
- Git-ready structure

---

## 📞 Need Help?

Baca dokumentasi lengkap di: **DOKUMENTASI_SWALDORF.md**

---

**Happy Coding! 🎉**
