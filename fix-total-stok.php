<?php

// Script untuk memperbaiki total_stok produk yang memiliki varian
// Jalankan: php fix-total-stok.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Produk;
use App\Models\ProductVariant;

echo "Memulai perbaikan total_stok...\n\n";

$produks = Produk::where('has_variants', true)->with('variants')->get();

$fixed = 0;
$errors = 0;

foreach ($produks as $produk) {
    try {
        $oldTotalStok = $produk->total_stok;

        // Hitung ulang total stok dari varian aktif
        $newTotalStok = $produk->variants()->where('is_active', true)->sum('stok');

        echo "Produk: {$produk->nama_produk}\n";
        echo "  - Total stok DB: {$oldTotalStok}\n";
        echo "  - Sum variant: {$newTotalStok}\n";
        echo "  - Jumlah variant: " . $produk->variants()->count() . "\n";

        if ($oldTotalStok != $newTotalStok) {
            $produk->total_stok = $newTotalStok;
            $produk->save();

            echo "  ✓ DIPERBAIKI: {$oldTotalStok} → {$newTotalStok}\n\n";
            $fixed++;
        } else {
            echo "  - Sudah benar\n\n";
        }
    } catch (\Exception $e) {
        echo "✗ Error pada {$produk->nama_produk}: {$e->getMessage()}\n";
        $errors++;
    }
}echo "\n=================================\n";
echo "Selesai!\n";
echo "Total diperbaiki: {$fixed} produk\n";
echo "Total error: {$errors} produk\n";
echo "=================================\n";
