<!DOCTYPE html>
<html>
<head>
    <title>Print Barcode - {{ $produk->nama_produk }}</title>
    <style>
        @media print {
            .no-print { display: none; }
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        
        .barcode-label {
            width: 300px;
            border: 2px solid #000;
            padding: 20px;
            margin: 20px auto;
            text-align: center;
            page-break-after: always;
        }
        
        .product-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .barcode-code {
            font-size: 14px;
            margin: 10px 0;
            font-family: monospace;
        }
        
        .barcode-image {
            margin: 15px 0;
        }
        
        .qr-image {
            margin: 15px 0;
        }
        
        .price {
            font-size: 20px;
            font-weight: bold;
            color: #e74c3c;
            margin-top: 10px;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .print-button:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">
        🖨️ Print
    </button>

    <div class="barcode-label">
        <div class="product-name">{{ $produk->nama_produk }}</div>
        
        <div class="barcode-code">{{ $produk->barcode }}</div>
        
        <div class="barcode-image">
            <img src="{{ route('produk.barcode', $produk->id_produk) }}" alt="Barcode" style="max-width: 250px;">
        </div>
        
        <div class="qr-image">
            <img src="{{ route('produk.qrcode', $produk->id_produk) }}" alt="QR Code" style="width: 150px;">
        </div>
        
        <div class="price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</div>
    </div>

    <script>
        // Auto print on load (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
