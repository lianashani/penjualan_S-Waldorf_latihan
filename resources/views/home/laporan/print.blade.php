<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan - S&Waldorf</title>
    <style>
        @media print {
            .no-print { display: none; }
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #000;
            padding-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        
        .header h2 {
            margin: 5px 0;
            font-size: 18px;
            color: #666;
        }
        
        .info {
            margin-bottom: 20px;
        }
        
        .info table {
            width: 100%;
        }
        
        .info td {
            padding: 5px;
        }
        
        .summary {
            background: #f0f0f0;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        
        .summary table {
            width: 100%;
        }
        
        .summary td {
            padding: 8px;
            font-size: 14px;
        }
        
        .summary .label {
            font-weight: bold;
            width: 200px;
        }
        
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        table.data th {
            background: #333;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 11px;
        }
        
        table.data td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        
        table.data tr:hover {
            background: #f5f5f5;
        }
        
        .footer {
            margin-top: 50px;
            text-align: right;
        }
        
        .signature {
            margin-top: 80px;
            text-align: center;
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

    <div class="header">
        <h1>S&WALDORF RETAIL FASHION</h1>
        <h2>LAPORAN PENJUALAN</h2>
        <p>Jl. Fashion Street No. 24, Bandung Marhas | Telp: (021) 98765432</p>
    </div>

    <div class="info">
        <table>
            <tr>
                <td width="150"><strong>Periode Laporan</strong></td>
                <td>: {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</td>
            </tr>
            <tr>
                <td><strong>Tanggal Cetak</strong></td>
                <td>: {{ now()->format('d F Y H:i') }}</td>
            </tr>
            <tr>
                <td><strong>Dicetak Oleh</strong></td>
                <td>: {{ Auth::user()->nama_user }} ({{ Auth::user()->role }})</td>
            </tr>
        </table>
    </div>

    <div class="summary">
        <table>
            <tr>
                <td class="label">Total Transaksi</td>
                <td>: <strong>{{ $totalTransaksi }}</strong> transaksi</td>
            </tr>
            <tr>
                <td class="label">Total Pendapatan</td>
                <td>: <strong style="color: #27ae60; font-size: 16px;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th width="30">No</th>
                <th width="120">Tanggal</th>
                <th>Kasir</th>
                <th>Pelanggan</th>
                <th width="120">Total</th>
                <th>Promo</th>
                <th width="80">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($penjualans as $penjualan)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $penjualan->tanggal_transaksi->format('d/m/Y H:i') }}</td>
                <td>{{ $penjualan->user->nama_user ?? '-' }}</td>
                <td>{{ $penjualan->pelanggan->nama_pelanggan ?? 'Guest' }}</td>
                <td>Rp {{ number_format($penjualan->total_bayar, 0, ',', '.') }}</td>
                <td>
                    @if($penjualan->promo)
                        {{ $penjualan->promo->kode_promo }} ({{ $penjualan->promo->persen }}%)
                    @else
                        -
                    @endif
                </td>
                <td>{{ ucfirst($penjualan->status_transaksi) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center;">Tidak ada data transaksi</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Bandung, {{ now()->format('d F Y') }}</p>
        <div class="signature">
            <p>_______________________</p>
            <p><strong>{{ Auth::user()->nama_user }}</strong></p>
            <p>{{ ucfirst(Auth::user()->role) }}</p>
        </div>
    </div>
</body>
</html>
