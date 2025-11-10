<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ str_pad($penjualan->id_penjualan, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            padding: 10mm;
            max-width: 80mm;
            margin: 0 auto;
        }

        .receipt {
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .header p {
            font-size: 10px;
            margin: 2px 0;
        }

        .info-section {
            margin-bottom: 10px;
            font-size: 11px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }

        .divider {
            border-bottom: 1px dashed #000;
            margin: 10px 0;
        }

        .items-table {
            width: 100%;
            margin: 10px 0;
        }

        .items-table th {
            text-align: left;
            padding: 5px 0;
            border-bottom: 1px solid #000;
            font-size: 11px;
        }

        .items-table td {
            padding: 5px 0;
            font-size: 11px;
        }

        .item-name {
            width: 45%;
        }

        .item-qty {
            width: 10%;
            text-align: center;
        }

        .item-price {
            width: 22%;
            text-align: right;
        }

        .item-subtotal {
            width: 23%;
            text-align: right;
        }

        .totals {
            margin-top: 10px;
            border-top: 1px solid #000;
            padding-top: 10px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
            font-size: 11px;
        }

        .total-row.grand-total {
            font-weight: bold;
            font-size: 14px;
            border-top: 2px solid #000;
            padding-top: 8px;
            margin-top: 8px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px dashed #000;
            font-size: 10px;
        }

        .footer p {
            margin: 3px 0;
        }

        @media print {
            body {
                padding: 0;
                margin: 0;
            }

            @page {
                size: 80mm auto;
                margin: 0;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <h1>S&WALDORF</h1>
            <p>Jl. Contoh No. 123, Jakarta</p>
            <p>Telp: 021-12345678</p>
            <p>www.swaldorf.com</p>
        </div>

        <!-- Transaction Info -->
        <div class="info-section">
            <div class="info-row">
                <span><strong>No Transaksi</strong></span>
                <span>#{{ str_pad($penjualan->id_penjualan, 5, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="info-row">
                <span><strong>Tanggal</strong></span>
                <span>{{ $penjualan->tanggal_transaksi->format('d/m/Y H:i') }}</span>
            </div>
            <div class="info-row">
                <span><strong>Kasir</strong></span>
                <span>{{ $penjualan->user->nama_user ?? 'Sistem' }}</span>
            </div>
            <div class="info-row">
                <span><strong>Pelanggan</strong></span>
                <span>{{ $penjualan->pelanggan->nama_pelanggan ?? 'Guest' }}</span>
            </div>
            @if($penjualan->payment_method)
            <div class="info-row">
                <span><strong>Metode Bayar</strong></span>
                <span>{{ strtoupper(str_replace('_', ' ', $penjualan->payment_method)) }}</span>
            </div>
            @endif
        </div>

        <div class="divider"></div>

        <!-- Items -->
        <table class="items-table">
            <thead>
                <tr>
                    <th class="item-name">Produk</th>
                    <th class="item-qty">Qty</th>
                    <th class="item-price">Harga</th>
                    <th class="item-subtotal">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penjualan->detailPenjualans as $detail)
                <tr>
                    <td class="item-name">{{ $detail->produk->nama_produk ?? 'Produk Tidak Diketahui' }}</td>
                    <td class="item-qty">{{ $detail->qty }}</td>
                    <td class="item-price">{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                    <td class="item-subtotal">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>

            @if($penjualan->promo && $diskon > 0)
            <div class="total-row">
                <span>Diskon ({{ $penjualan->promo->persen }}%):</span>
                <span>- Rp {{ number_format($diskon, 0, ',', '.') }}</span>
            </div>
            @endif

            <div class="total-row grand-total">
                <span>TOTAL:</span>
                <span>Rp {{ number_format($penjualan->total_bayar, 0, ',', '.') }}</span>
            </div>

            @if($penjualan->payment_method === 'cash' && $penjualan->kembalian > 0)
            <div class="total-row">
                <span>Bayar:</span>
                <span>Rp {{ number_format($penjualan->total_bayar + $penjualan->kembalian, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span>Kembalian:</span>
                <span>Rp {{ number_format($penjualan->kembalian, 0, ',', '.') }}</span>
            </div>
            @endif
        </div>        <!-- Footer -->
        <div class="footer">
            <p><strong>TERIMA KASIH</strong></p>
            <p>Barang yang sudah dibeli tidak dapat dikembalikan</p>
            <p>Simpan struk ini sebagai bukti pembayaran</p>
            <p style="margin-top: 10px;">{{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
