# Dokumentasi Aplikasi Penjualan S&Waldorf Retail Fashion

## 📋 Deskripsi Proyek

Aplikasi pengelolaan data transaksi, pelanggan, dan stok produk untuk S&Waldorf Retail Fashion dengan fitur perhitungan diskon otomatis. Aplikasi ini dirancang untuk mencatat semua transaksi secara akurat dengan validasi diskon maksimal 100%.

## 🎯 Fitur Utama

### 1. **Dashboard Inventori Penjualan**
- Statistik real-time: Jumlah User, Kategori, Produk, Transaksi
- Total Pendapatan dari transaksi selesai
- Monitoring Stok Rendah (≤10 items)
- Tabel Status Stok Barang dengan kategori
- Riwayat Penjualan Terbaru dengan informasi promo

### 2. **Sistem Perhitungan Diskon**
- Input harga dan persentase diskon dari pengguna
- Validasi diskon tidak melebihi 100%
- Rumus: `total_harga = harga - (harga * (diskon / 100))`
- Tampilan nilai diskon dan total setelah diskon
- Perhitungan kembalian otomatis

### 3. **Manajemen Transaksi Penjualan**
- Form transaksi dengan pemilihan produk multiple
- Integrasi kode promo dengan validasi periode aktif
- Perhitungan subtotal, diskon, dan total otomatis
- Validasi stok produk sebelum transaksi
- Detail transaksi lengkap dengan informasi diskon
- Cetak struk penjualan

### 4. **Manajemen Data Master**
- **Produk**: Nama, kategori, ukuran, warna, stok, harga, deskripsi, gambar
- **Kategori**: Pengelompokan produk fashion
- **Pelanggan**: Data pelanggan dengan membership
- **Promo**: Kode promo, persentase diskon, periode aktif

## 🗄️ Struktur Database

### Entity Relationship Diagram (ERD)

```
┌─────────────┐       ┌──────────────┐       ┌─────────────────┐
│   Users     │       │  Pelanggans  │       │   Memberships   │
├─────────────┤       ├──────────────┤       ├─────────────────┤
│ id (PK)     │       │ id_pelanggan │       │ id_membership   │
│ name        │       │ nama_pelanggan│      │ nama_membership │
│ email       │       │ email        │       │ diskon          │
│ password    │       │ status       │       └─────────────────┘
└─────────────┘       │ id_membership│              ▲
       │              └──────────────┘              │
       │                     │                      │
       │                     │                      │
       ▼                     ▼                      │
┌─────────────┐       ┌──────────────┐             │
│ Penjualans  │◄──────┤    Promos    │             │
├─────────────┤       ├──────────────┤             │
│id_penjualan │       │ id_promo (PK)│             │
│ id_user (FK)│       │ kode_promo   │             │
│id_pelanggan │       │ persen       │             │
│ id_promo(FK)│       │ tanggal_mulai│             │
│ total_bayar │       │tanggal_selesai│            │
│ kembalian   │       │ status       │             │
│status_trans │       └──────────────┘             │
└─────────────┘                                    │
       │                                           │
       │                                           │
       ▼                                           │
┌──────────────────┐                               │
│DetailPenjualans  │                               │
├──────────────────┤                               │
│ id_detail (PK)   │                               │
│id_penjualan (FK) │                               │
│ id_produk (FK)   │                               │
│ qty              │                               │
│ harga_satuan     │                               │
│ subtotal         │                               │
└──────────────────┘                               │
       │                                           │
       ▼                                           │
┌─────────────┐       ┌──────────────┐             │
│  Produks    │◄──────┤  Kategoris   │             │
├─────────────┤       ├──────────────┤             │
│id_produk(PK)│       │id_kategori   │             │
│ nama_produk │       │nama_kategori │             │
│id_kategori  │       └──────────────┘             │
│ ukuran      │                                    │
│ warna       │                                    │
│ stok        │                                    │
│ harga       │                                    │
│ deskripsi   │                                    │
│ gambar      │                                    │
└─────────────┘                                    │
```

### Tabel Database

#### 1. **users**
- `id` (PK)
- `name`
- `email` (unique)
- `password`
- `timestamps`

#### 2. **kategoris**
- `id_kategori` (PK)
- `nama_kategori`
- `timestamps`

#### 3. **produks**
- `id_produk` (PK)
- `nama_produk`
- `id_kategori` (FK → kategoris)
- `ukuran`
- `warna`
- `stok` (default: 0)
- `harga` (decimal 10,2)
- `deskripsi` (text, nullable)
- `gambar` (nullable)
- `timestamps`

#### 4. **pelanggans**
- `id_pelanggan` (PK)
- `nama_pelanggan`
- `email` (unique)
- `password` (nullable)
- `status` (enum: aktif, nonaktif)
- `tanggal_daftar`
- `id_membership` (FK → memberships, nullable)
- `timestamps`

#### 5. **promos**
- `id_promo` (PK)
- `kode_promo` (unique)
- `persen` (decimal 5,2) - max 100%
- `tanggal_mulai` (date)
- `tanggal_selesai` (date)
- `status` (enum: aktif, nonaktif)
- `timestamps`

#### 6. **penjualans**
- `id_penjualan` (PK)
- `id_user` (FK → users)
- `id_pelanggan` (FK → pelanggans, nullable)
- `id_promo` (FK → promos, nullable)
- `total_bayar` (decimal 10,2)
- `kembalian` (decimal 10,2)
- `status_transaksi` (enum: pending, selesai, batal)
- `tanggal_transaksi` (datetime)
- `timestamps`

#### 7. **detail_penjualans**
- `id_detail` (PK)
- `id_penjualan` (FK → penjualans, cascade delete)
- `id_produk` (FK → produks, cascade delete)
- `qty` (integer)
- `harga_satuan` (decimal 10,2)
- `subtotal` (decimal 10,2)
- `timestamps`

## 🔧 Instalasi dan Setup

### Prerequisites
- PHP >= 8.1
- Composer
- MySQL/MariaDB
- Laravel 11.x

### Langkah Instalasi

1. **Clone atau Copy Project**
   ```bash
   cd "d:\DATA SISWA XII PPLG 2\Nurliana sani\Nurliana_sani\LATIHAN-UJIKOM\SWaldorf"
   ```

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Setup Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Konfigurasi Database** (Edit `.env`)
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=swaldorf_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Buat Database**
   ```sql
   CREATE DATABASE swaldorf_db;
   ```

6. **Jalankan Migration**
   ```bash
   php artisan migrate
   ```

7. **Seed Sample Data**
   ```bash
   php artisan db:seed --class=SWaldorfSeeder
   ```

8. **Jalankan Server**
   ```bash
   php artisan serve
   ```

9. **Akses Aplikasi**
   ```
   http://localhost:8000
   ```

## 👤 Akun Default

Setelah seeding, gunakan akun berikut:

- **Admin**: admin@swaldorf.com / password
- **Kasir**: kasir1@swaldorf.com / password

## 📊 Data Sample

### Kategori (5 items)
- Pakaian Wanita
- Pakaian Pria
- Aksesoris
- Sepatu
- Tas

### Produk (12 items)
- Dress Elegant Black - Rp 450.000
- Blouse Floral - Rp 280.000
- Rok Midi Plisket - Rp 320.000
- Kemeja Formal Putih - Rp 350.000
- Celana Chino - Rp 380.000
- Blazer Slim Fit - Rp 750.000
- Kalung Emas 18K - Rp 1.200.000
- Jam Tangan Classic - Rp 950.000
- Heels Stiletto - Rp 580.000
- Sneakers Casual - Rp 650.000
- Handbag Kulit - Rp 890.000
- Backpack Travel - Rp 720.000

### Promo Aktif (4 items)
- **GRAND10** - Diskon 10%
- **FLASH20** - Diskon 20%
- **MEMBER15** - Diskon 15%
- **MEGA50** - Diskon 50%

### Pelanggan (4 items)
- Siti Nurhaliza
- Budi Santoso
- Dewi Lestari
- Ahmad Rizki

## 🧮 Logika Perhitungan Diskon

### Formula Diskon
```php
// Input
$harga = 450000;
$diskon_persen = 10; // max 100%

// Validasi
if ($diskon_persen > 100) {
    throw new Exception("Diskon tidak boleh melebihi 100%");
}

// Perhitungan
$nilai_diskon = $harga * ($diskon_persen / 100);
$total_harga = $harga - $nilai_diskon;

// Output
// nilai_diskon = 45000
// total_harga = 405000
```

### Contoh Perhitungan

#### Contoh 1: Diskon 10%
- Harga: Rp 450.000
- Diskon: 10%
- Nilai Diskon: Rp 45.000
- Total: Rp 405.000

#### Contoh 2: Diskon 20% untuk Multiple Items
- Item 1: Rp 350.000
- Item 2: Rp 320.000
- Subtotal: Rp 670.000
- Diskon 20%: Rp 134.000
- Total: Rp 536.000

#### Contoh 3: Tanpa Diskon
- Harga: Rp 1.200.000
- Diskon: 0%
- Nilai Diskon: Rp 0
- Total: Rp 1.200.000

## 🛣️ Route List

### Dashboard
- `GET /` - Dashboard inventori penjualan

### Penjualan
- `GET /penjualan` - Daftar transaksi
- `GET /penjualan/create` - Form transaksi baru
- `POST /penjualan` - Simpan transaksi
- `GET /penjualan/{id}` - Detail transaksi
- `POST /penjualan/calculate-discount` - API perhitungan diskon

### Produk
- `GET /produk` - Daftar produk
- `GET /produk/create` - Form tambah produk
- `POST /produk` - Simpan produk
- `GET /produk/{id}/edit` - Form edit produk
- `PUT /produk/{id}` - Update produk
- `DELETE /produk/{id}` - Hapus produk

### Kategori
- `GET /kategori` - Daftar kategori
- Resource routes untuk CRUD

### Pelanggan
- `GET /pelanggan` - Daftar pelanggan
- Resource routes untuk CRUD

### Promo
- `GET /promo` - Daftar promo
- Resource routes untuk CRUD

## 🔍 Fitur Validasi

### Validasi Input Transaksi
1. **Produk**: Harus dipilih minimal 1 item
2. **Qty**: Harus >= 1 dan tidak melebihi stok
3. **Diskon**: Harus 0-100%
4. **Pembayaran**: Harus >= total setelah diskon
5. **Promo**: Harus aktif dan dalam periode valid

### Validasi Stok
- Cek ketersediaan stok sebelum transaksi
- Update stok otomatis setelah transaksi berhasil
- Alert jika stok tidak mencukupi

### Validasi Promo
- Cek status promo (aktif/nonaktif)
- Validasi periode promo (tanggal_mulai - tanggal_selesai)
- Validasi persentase diskon (max 100%)

## 📱 Fitur Tambahan

### Dashboard Features
- Real-time statistics
- Low stock alerts
- Recent transactions monitoring
- Revenue tracking

### Transaction Features
- Multiple product selection
- Automatic discount calculation
- Stock validation
- Change calculation
- Print receipt

### Reporting
- Transaction history
- Stock status report
- Revenue summary
- Discount usage tracking

## 🧪 Testing

### Test Scenario 1: Transaksi dengan Diskon 10%
1. Pilih produk: Dress Elegant Black (Rp 450.000)
2. Pilih promo: GRAND10 (10%)
3. Hasil:
   - Subtotal: Rp 450.000
   - Diskon: Rp 45.000
   - Total: Rp 405.000
   - Bayar: Rp 500.000
   - Kembalian: Rp 95.000

### Test Scenario 2: Transaksi Multiple Items dengan Diskon 20%
1. Pilih produk 1: Kemeja Formal (Rp 350.000)
2. Pilih produk 2: Rok Midi (Rp 320.000)
3. Pilih promo: FLASH20 (20%)
4. Hasil:
   - Subtotal: Rp 670.000
   - Diskon: Rp 134.000
   - Total: Rp 536.000

### Test Scenario 3: Validasi Stok Tidak Cukup
1. Pilih produk dengan stok 5
2. Input qty: 10
3. Hasil: Error "Stok tidak mencukupi"

### Test Scenario 4: Validasi Diskon > 100%
1. Buat promo dengan diskon 150%
2. Hasil: Error "Persentase diskon tidak boleh melebihi 100%"

## 📝 Catatan Penting

1. **Backup Database**: Selalu backup database sebelum testing
2. **Validasi Input**: Semua input divalidasi di server-side dan client-side
3. **Transaction Safety**: Menggunakan database transaction untuk data integrity
4. **Stock Management**: Stok otomatis berkurang setelah transaksi selesai
5. **Promo Period**: Promo hanya berlaku dalam periode yang ditentukan

## 🔐 Security Features

- Password hashing menggunakan bcrypt
- CSRF protection pada semua form
- Input validation dan sanitization
- SQL injection prevention (Eloquent ORM)
- XSS protection

## 📞 Support

Untuk pertanyaan atau bantuan, hubungi:
- Developer: Nurliana Sani
- Kelas: XII PPLG 2
- Project: Latihan Ujikom S&Waldorf

## 📄 License

Project ini dibuat untuk keperluan latihan ujikom.

---

**Dibuat dengan ❤️ untuk S&Waldorf Retail Fashion**
