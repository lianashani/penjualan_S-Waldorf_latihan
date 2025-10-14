<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\ProductRating;
use Illuminate\Support\Facades\Storage;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        // Update existing products to support variants
        $produks = Produk::all();

        foreach ($produks as $produk) {
            // Update product to support variants
            $produk->update([
                'has_variants' => true,
                'is_active' => true,
                'slug' => \Str::slug($produk->nama_produk)
            ]);

            // Create variants based on existing product data
            $sizes = ['S', 'M', 'L', 'XL'];
            $colors = ['Hitam', 'Putih', 'Merah', 'Biru', 'Hijau'];

            // Create 2-4 variants per product
            $variantCount = rand(2, 4);

            for ($i = 0; $i < $variantCount; $i++) {
                $size = $sizes[array_rand($sizes)];
                $color = $colors[array_rand($colors)];

                // Check if variant already exists
                $existingVariant = ProductVariant::where('id_produk', $produk->id_produk)
                    ->where('ukuran', $size)
                    ->where('warna', $color)
                    ->first();

                if (!$existingVariant) {
                    $variant = ProductVariant::create([
                        'id_produk' => $produk->id_produk,
                        'ukuran' => $size,
                        'warna' => $color,
                        'kode_warna' => $this->getColorCode($color),
                        'stok' => rand(10, 100),
                        'harga' => $produk->harga + rand(-50000, 50000), // Slight price variation
                        'is_active' => true
                    ]);

                    // Create some sample ratings for variants
                    if (rand(1, 3) === 1) {
                        ProductRating::create([
                            'id_produk' => $produk->id_produk,
                            'nama_pengguna' => 'Pelanggan ' . rand(1, 100),
                            'email_pengguna' => 'customer' . rand(1, 100) . '@example.com',
                            'rating' => rand(3, 5),
                            'komentar' => $this->getRandomComment(),
                            'is_approved' => rand(0, 1) === 1,
                            'is_verified_purchase' => rand(0, 1) === 1
                        ]);
                    }
                }
            }

            // Create sample images for products
            if (rand(1, 2) === 1) {
                ProductImage::create([
                    'id_produk' => $produk->id_produk,
                    'gambar' => $produk->gambar ?? 'produk/sample-product.jpg',
                    'alt_text' => $produk->nama_produk,
                    'urutan' => 0,
                    'is_primary' => true,
                    'is_active' => true
                ]);
            }
        }

        // Create some additional sample products with variants
        $this->createSampleProducts();
    }

    private function createSampleProducts()
    {
        $sampleProducts = [
            [
                'nama_produk' => 'T-Shirt Premium Cotton',
                'id_kategori' => 1,
                'harga' => 150000,
                'deskripsi' => 'T-shirt premium dengan bahan cotton 100% yang nyaman dipakai sehari-hari.',
                'variants' => [
                    ['ukuran' => 'S', 'warna' => 'Hitam', 'kode_warna' => '#000000', 'stok' => 50, 'harga' => 150000],
                    ['ukuran' => 'M', 'warna' => 'Hitam', 'kode_warna' => '#000000', 'stok' => 75, 'harga' => 150000],
                    ['ukuran' => 'L', 'warna' => 'Hitam', 'kode_warna' => '#000000', 'stok' => 60, 'harga' => 150000],
                    ['ukuran' => 'S', 'warna' => 'Putih', 'kode_warna' => '#FFFFFF', 'stok' => 45, 'harga' => 150000],
                    ['ukuran' => 'M', 'warna' => 'Putih', 'kode_warna' => '#FFFFFF', 'stok' => 80, 'harga' => 150000],
                    ['ukuran' => 'L', 'warna' => 'Putih', 'kode_warna' => '#FFFFFF', 'stok' => 70, 'harga' => 150000],
                ]
            ],
            [
                'nama_produk' => 'Jeans Slim Fit',
                'id_kategori' => 2,
                'harga' => 350000,
                'deskripsi' => 'Jeans slim fit dengan bahan denim berkualitas tinggi dan potongan yang pas.',
                'variants' => [
                    ['ukuran' => '28', 'warna' => 'Biru Tua', 'kode_warna' => '#1E3A8A', 'stok' => 30, 'harga' => 350000],
                    ['ukuran' => '30', 'warna' => 'Biru Tua', 'kode_warna' => '#1E3A8A', 'stok' => 40, 'harga' => 350000],
                    ['ukuran' => '32', 'warna' => 'Biru Tua', 'kode_warna' => '#1E3A8A', 'stok' => 35, 'harga' => 350000],
                    ['ukuran' => '28', 'warna' => 'Hitam', 'kode_warna' => '#000000', 'stok' => 25, 'harga' => 350000],
                    ['ukuran' => '30', 'warna' => 'Hitam', 'kode_warna' => '#000000', 'stok' => 35, 'harga' => 350000],
                ]
            ],
            [
                'nama_produk' => 'Sneakers Casual',
                'id_kategori' => 3,
                'harga' => 450000,
                'deskripsi' => 'Sneakers casual yang nyaman untuk aktivitas sehari-hari dengan desain yang trendy.',
                'variants' => [
                    ['ukuran' => '39', 'warna' => 'Putih', 'kode_warna' => '#FFFFFF', 'stok' => 20, 'harga' => 450000],
                    ['ukuran' => '40', 'warna' => 'Putih', 'kode_warna' => '#FFFFFF', 'stok' => 25, 'harga' => 450000],
                    ['ukuran' => '41', 'warna' => 'Putih', 'kode_warna' => '#FFFFFF', 'stok' => 30, 'harga' => 450000],
                    ['ukuran' => '39', 'warna' => 'Hitam', 'kode_warna' => '#000000', 'stok' => 18, 'harga' => 450000],
                    ['ukuran' => '40', 'warna' => 'Hitam', 'kode_warna' => '#000000', 'stok' => 22, 'harga' => 450000],
                    ['ukuran' => '41', 'warna' => 'Hitam', 'kode_warna' => '#000000', 'stok' => 28, 'harga' => 450000],
                ]
            ]
        ];

        foreach ($sampleProducts as $productData) {
            $variants = $productData['variants'];
            unset($productData['variants']);

            $produk = Produk::create(array_merge($productData, [
                'has_variants' => true,
                'is_active' => true,
                'is_featured' => rand(0, 1) === 1,
                'slug' => \Str::slug($productData['nama_produk'])
            ]));

            foreach ($variants as $variantData) {
                ProductVariant::create(array_merge($variantData, [
                    'id_produk' => $produk->id_produk,
                    'is_active' => true
                ]));
            }

            // Create sample ratings
            for ($i = 0; $i < rand(3, 8); $i++) {
                ProductRating::create([
                    'id_produk' => $produk->id_produk,
                    'nama_pengguna' => 'Pelanggan ' . rand(1, 100),
                    'email_pengguna' => 'customer' . rand(1, 100) . '@example.com',
                    'rating' => rand(3, 5),
                    'komentar' => $this->getRandomComment(),
                    'is_approved' => rand(0, 1) === 1,
                    'is_verified_purchase' => rand(0, 1) === 1
                ]);
            }
        }
    }

    private function getColorCode($color)
    {
        $colorCodes = [
            'Hitam' => '#000000',
            'Putih' => '#FFFFFF',
            'Merah' => '#DC2626',
            'Biru' => '#2563EB',
            'Hijau' => '#16A34A',
            'Biru Tua' => '#1E3A8A',
            'Abu-abu' => '#6B7280',
            'Coklat' => '#92400E',
            'Pink' => '#EC4899',
            'Kuning' => '#EAB308'
        ];

        return $colorCodes[$color] ?? '#CCCCCC';
    }

    private function getRandomComment()
    {
        $comments = [
            'Produk berkualitas tinggi, sangat puas dengan pembelian ini!',
            'Bahan bagus dan nyaman dipakai, recommended!',
            'Sesuai dengan deskripsi, pengiriman cepat.',
            'Kualitas oke, harga terjangkau.',
            'Produk original, packing rapi.',
            'Sangat memuaskan, akan order lagi.',
            'Kualitas sesuai harga, recommended untuk yang lain.',
            'Produk bagus, seller responsif.',
            'Sesuai ekspektasi, kualitas terjamin.',
            'Mantap, kualitas produk sangat baik!'
        ];

        return $comments[array_rand($comments)];
    }
}
