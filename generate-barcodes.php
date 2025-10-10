<?php
/**
 * Script untuk generate barcode untuk produk yang sudah ada
 * Jalankan: php generate-barcodes.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Produk;

echo "🔢 Generating barcodes for existing products...\n\n";

$produks = Produk::whereNull('barcode')->get();

if ($produks->count() == 0) {
    echo "✓ All products already have barcodes!\n";
    exit;
}

foreach ($produks as $produk) {
    $barcode = 'PRD' . str_pad($produk->id_produk, 6, '0', STR_PAD_LEFT);
    $produk->barcode = $barcode;
    $produk->qr_code = $barcode;
    $produk->save();
    
    echo "✓ {$produk->nama_produk} → Barcode: {$barcode}\n";
}

echo "\n✅ Successfully generated barcodes for {$produks->count()} products!\n";
