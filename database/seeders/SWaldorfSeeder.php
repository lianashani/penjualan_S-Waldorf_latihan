<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SWaldorfSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('detail_penjualans')->truncate();
        DB::table('penjualans')->truncate();
        DB::table('produks')->truncate();
        DB::table('pelanggans')->truncate();
        DB::table('promos')->truncate();
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Insert Users (Admin & Kasir)
        DB::table('users')->insert([
            [
                'id_user' => 1,
                'nama_user' => 'Liana',
                'email' => 'admin@swaldorf.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 2,
                'nama_user' => 'Shani',
                'email' => 'kasir@swaldorf.com',
                'password' => Hash::make('password'),
                'role' => 'kasir',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Insert Kategoris (Categories)
        $kategoris = [
            ['id_kategori' => 1, 'nama_kategori' => 'Pakaian Wanita', 'created_at' => now(), 'updated_at' => now()],
            ['id_kategori' => 2, 'nama_kategori' => 'Pakaian Pria', 'created_at' => now(), 'updated_at' => now()],
            ['id_kategori' => 3, 'nama_kategori' => 'Aksesoris', 'created_at' => now(), 'updated_at' => now()],
            ['id_kategori' => 4, 'nama_kategori' => 'Sepatu', 'created_at' => now(), 'updated_at' => now()],
            ['id_kategori' => 5, 'nama_kategori' => 'Tas', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('kategoris')->insert($kategoris);

        // Insert Produks (Products)
        $produks = [
            // Pakaian Wanita
            ['id_produk' => 1, 'nama_produk' => 'Dress Elegant Black', 'id_kategori' => 1, 'ukuran' => 'M', 'warna' => 'Hitam', 'stok' => 15, 'harga' => 450000, 'gambar' => 'produk/dress-elegant.jpg', 'deskripsi' => 'Dress elegant untuk acara formal', 'created_at' => now(), 'updated_at' => now()],
            ['id_produk' => 2, 'nama_produk' => 'Blouse Floral', 'id_kategori' => 1, 'ukuran' => 'L', 'warna' => 'Pink', 'stok' => 8, 'harga' => 280000, 'gambar' => 'produk/blouse-floral.jpg', 'deskripsi' => 'Blouse dengan motif bunga', 'created_at' => now(), 'updated_at' => now()],
            ['id_produk' => 3, 'nama_produk' => 'Rok Midi Plisket', 'id_kategori' => 1, 'ukuran' => 'S', 'warna' => 'Navy', 'stok' => 20, 'harga' => 320000, 'gambar' => 'produk/rok-midi.jpg', 'deskripsi' => 'Rok midi dengan plisket', 'created_at' => now(), 'updated_at' => now()],
            
            // Pakaian Pria
            ['id_produk' => 4, 'nama_produk' => 'Kemeja Formal Putih', 'id_kategori' => 2, 'ukuran' => 'L', 'warna' => 'Putih', 'stok' => 25, 'harga' => 350000, 'gambar' => 'produk/kemeja-formal.jpg', 'deskripsi' => 'Kemeja formal untuk kerja', 'created_at' => now(), 'updated_at' => now()],
            ['id_produk' => 5, 'nama_produk' => 'Celana Chino', 'id_kategori' => 2, 'ukuran' => 'M', 'warna' => 'Khaki', 'stok' => 5, 'harga' => 380000, 'gambar' => 'produk/celana-chino.jpg', 'deskripsi' => 'Celana chino premium', 'created_at' => now(), 'updated_at' => now()],
            ['id_produk' => 6, 'nama_produk' => 'Blazer Slim Fit', 'id_kategori' => 2, 'ukuran' => 'L', 'warna' => 'Abu-abu', 'stok' => 10, 'harga' => 750000, 'gambar' => 'produk/blazer-slim.jpg', 'deskripsi' => 'Blazer slim fit modern', 'created_at' => now(), 'updated_at' => now()],
            
            // Aksesoris
            ['id_produk' => 7, 'nama_produk' => 'Kalung Emas 18K', 'id_kategori' => 3, 'ukuran' => 'One Size', 'warna' => 'Emas', 'stok' => 12, 'harga' => 1200000, 'gambar' => 'produk/kalung-emas.jpg', 'deskripsi' => 'Kalung emas 18 karat', 'created_at' => now(), 'updated_at' => now()],
            ['id_produk' => 8, 'nama_produk' => 'Jam Tangan Classic', 'id_kategori' => 3, 'ukuran' => 'One Size', 'warna' => 'Silver', 'stok' => 7, 'harga' => 950000, 'gambar' => 'produk/jam-tangan.jpg', 'deskripsi' => 'Jam tangan classic design', 'created_at' => now(), 'updated_at' => now()],
            
            // Sepatu
            ['id_produk' => 9, 'nama_produk' => 'Heels Stiletto', 'id_kategori' => 4, 'ukuran' => '38', 'warna' => 'Merah', 'stok' => 3, 'harga' => 580000, 'gambar' => 'produk/heels-stiletto.jpg', 'deskripsi' => 'Heels tinggi 10cm', 'created_at' => now(), 'updated_at' => now()],
            ['id_produk' => 10, 'nama_produk' => 'Sneakers Casual', 'id_kategori' => 4, 'ukuran' => '42', 'warna' => 'Putih', 'stok' => 18, 'harga' => 650000, 'gambar' => 'produk/sneakers-casual.jpg', 'deskripsi' => 'Sneakers casual unisex', 'created_at' => now(), 'updated_at' => now()],
            
            // Tas
            ['id_produk' => 11, 'nama_produk' => 'Handbag Kulit', 'id_kategori' => 5, 'ukuran' => 'Medium', 'warna' => 'Coklat', 'stok' => 9, 'harga' => 890000, 'gambar' => 'produk/handbag-kulit.jpg', 'deskripsi' => 'Handbag kulit asli', 'created_at' => now(), 'updated_at' => now()],
            ['id_produk' => 12, 'nama_produk' => 'Backpack Travel', 'id_kategori' => 5, 'ukuran' => 'Large', 'warna' => 'Hitam', 'stok' => 14, 'harga' => 720000, 'gambar' => 'produk/backpack-travel.jpg', 'deskripsi' => 'Backpack untuk travel', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('produks')->insert($produks);

        // Insert Pelanggans (Customers)
        $pelanggans = [
            ['id_pelanggan' => 1, 'nama_pelanggan' => 'Siti Nurhaliza', 'email' => 'siti@email.com', 'status' => 'aktif', 'tanggal_daftar' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['id_pelanggan' => 2, 'nama_pelanggan' => 'Budi Santoso', 'email' => 'budi@email.com', 'status' => 'aktif', 'tanggal_daftar' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['id_pelanggan' => 3, 'nama_pelanggan' => 'Dewi Lestari', 'email' => 'dewi@email.com', 'status' => 'aktif', 'tanggal_daftar' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['id_pelanggan' => 4, 'nama_pelanggan' => 'Ahmad Rizki', 'email' => 'ahmad@email.com', 'status' => 'aktif', 'tanggal_daftar' => now(), 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('pelanggans')->insert($pelanggans);

        // Insert Promos (Discounts)
        $promos = [
            [
                'id_promo' => 1,
                'kode_promo' => 'GRAND10',
                'persen' => 10.00,
                'tanggal_mulai' => Carbon::now()->subDays(5),
                'tanggal_selesai' => Carbon::now()->addDays(25),
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_promo' => 2,
                'kode_promo' => 'FLASH20',
                'persen' => 20.00,
                'tanggal_mulai' => Carbon::now()->subDays(2),
                'tanggal_selesai' => Carbon::now()->addDays(5),
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_promo' => 3,
                'kode_promo' => 'MEMBER15',
                'persen' => 15.00,
                'tanggal_mulai' => Carbon::now()->subDays(10),
                'tanggal_selesai' => Carbon::now()->addDays(50),
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_promo' => 4,
                'kode_promo' => 'MEGA50',
                'persen' => 50.00,
                'tanggal_mulai' => Carbon::now()->subDays(1),
                'tanggal_selesai' => Carbon::now()->addDays(3),
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        DB::table('promos')->insert($promos);

        // Insert Sample Penjualans (Sales Transactions)
        $penjualans = [
            [
                'id_penjualan' => 1,
                'id_user' => 1,
                'id_pelanggan' => 1,
                'id_promo' => 1,
                'total_bayar' => 405000, // 450000 - 10% = 405000
                'kembalian' => 95000,
                'status_transaksi' => 'selesai',
                'tanggal_transaksi' => Carbon::now()->subDays(3),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_penjualan' => 2,
                'id_user' => 2,
                'id_pelanggan' => 2,
                'id_promo' => 2,
                'total_bayar' => 520000, // (350000 + 320000) - 20% = 536000
                'kembalian' => 80000,
                'status_transaksi' => 'selesai',
                'tanggal_transaksi' => Carbon::now()->subDays(2),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_penjualan' => 3,
                'id_user' => 1,
                'id_pelanggan' => null,
                'id_promo' => null,
                'total_bayar' => 1200000,
                'kembalian' => 300000,
                'status_transaksi' => 'selesai',
                'tanggal_transaksi' => Carbon::now()->subDays(1),
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        DB::table('penjualans')->insert($penjualans);

        // Insert Detail Penjualans
        $detailPenjualans = [
            // Transaction 1
            ['id_penjualan' => 1, 'id_produk' => 1, 'qty' => 1, 'harga_satuan' => 450000, 'subtotal' => 450000, 'created_at' => now(), 'updated_at' => now()],
            
            // Transaction 2
            ['id_penjualan' => 2, 'id_produk' => 4, 'qty' => 1, 'harga_satuan' => 350000, 'subtotal' => 350000, 'created_at' => now(), 'updated_at' => now()],
            ['id_penjualan' => 2, 'id_produk' => 3, 'qty' => 1, 'harga_satuan' => 320000, 'subtotal' => 320000, 'created_at' => now(), 'updated_at' => now()],
            
            // Transaction 3
            ['id_penjualan' => 3, 'id_produk' => 7, 'qty' => 1, 'harga_satuan' => 1200000, 'subtotal' => 1200000, 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('detail_penjualans')->insert($detailPenjualans);

        // Create Sample Members
        \App\Models\Member::create([
            'nama_member' => 'Member Test',
            'email' => 'member@swaldorf.com',
            'password' => Hash::make('password'),
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Member Test No. 1',
            'points' => 100,
            'status' => 'aktif'
        ]);

        // Seed completed
        $this->command->info('✅ Seeding completed successfully!');
    }
}
