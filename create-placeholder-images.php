<?php
/**
 * Script untuk membuat placeholder images untuk produk
 * Jalankan: php create-placeholder-images.php
 */

echo "🎨 Creating placeholder images for products...\n\n";

$imagePath = __DIR__ . '/storage/app/public/produk/';

// Daftar produk yang perlu gambar
$products = [
    'dress-elegant.jpg' => ['Dress Elegant', '#1e3a8a'],
    'blouse-floral.jpg' => ['Blouse Floral', '#ec4899'],
    'rok-midi.jpg' => ['Rok Midi', '#1e40af'],
    'kemeja-formal.jpg' => ['Kemeja Formal', '#ffffff'],
    'celana-chino.jpg' => ['Celana Chino', '#92400e'],
    'blazer-slim.jpg' => ['Blazer Slim', '#6b7280'],
    'kalung-emas.jpg' => ['Kalung Emas', '#fbbf24'],
    'jam-tangan.jpg' => ['Jam Tangan', '#94a3b8'],
    'heels-stiletto.jpg' => ['Heels Stiletto', '#dc2626'],
    'sneakers-casual.jpg' => ['Sneakers', '#f3f4f6'],
    'handbag-kulit.jpg' => ['Handbag', '#92400e'],
    'backpack-travel.jpg' => ['Backpack', '#1f2937'],
];

foreach ($products as $filename => $data) {
    $filepath = $imagePath . $filename;
    [$name, $color] = $data;
    
    // Create 400x400 image
    $image = imagecreatetruecolor(400, 400);
    
    // Set background color
    $bgColor = hexToRgb($color);
    $bg = imagecolorallocate($image, $bgColor[0], $bgColor[1], $bgColor[2]);
    imagefill($image, 0, 0, $bg);
    
    // Add text
    $white = imagecolorallocate($image, 255, 255, 255);
    $black = imagecolorallocate($image, 0, 0, 0);
    $textColor = ($color == '#ffffff' || $color == '#f3f4f6') ? $black : $white;
    
    // Add product name
    $fontSize = 5;
    $textWidth = imagefontwidth($fontSize) * strlen($name);
    $textHeight = imagefontheight($fontSize);
    $x = (400 - $textWidth) / 2;
    $y = (400 - $textHeight) / 2;
    imagestring($image, $fontSize, $x, $y, $name, $textColor);
    
    // Add "S&Waldorf" watermark
    $watermark = "S&Waldorf";
    $wmWidth = imagefontwidth(3) * strlen($watermark);
    $wmX = (400 - $wmWidth) / 2;
    imagestring($image, 3, $wmX, 350, $watermark, $textColor);
    
    // Save image
    imagejpeg($image, $filepath, 90);
    imagedestroy($image);
    
    echo "✓ Created: $filename\n";
}

function hexToRgb($hex) {
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) == 3) {
        $r = hexdec(str_repeat(substr($hex, 0, 1), 2));
        $g = hexdec(str_repeat(substr($hex, 1, 1), 2));
        $b = hexdec(str_repeat(substr($hex, 2, 1), 2));
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }
    return [$r, $g, $b];
}

echo "\n✅ All placeholder images created successfully!\n";
echo "📁 Location: storage/app/public/produk/\n";
