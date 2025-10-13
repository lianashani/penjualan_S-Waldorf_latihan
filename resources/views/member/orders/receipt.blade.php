<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Struk Pesanan {{ $order->order_number ?? ('#'.$order->id_order) }}</title>
  <style>
    body{ font-family: Arial, Helvetica, sans-serif; color:#111; margin:0; padding:0; background:#fff }
    .wrap{ max-width:720px; margin:0 auto; padding:24px }
    .header{ display:flex; justify-content:space-between; align-items:center; border-bottom:2px solid #000; padding-bottom:12px; margin-bottom:16px }
    .brand{ font-weight:800; font-size:18px }
    .meta{ font-size:12px; color:#333 }
    .badge{ display:inline-block; border:1px solid #000; padding:2px 8px; border-radius:999px; font-size:12px; font-weight:700 }
    table{ width:100%; border-collapse:collapse; margin-top:10px }
    th,td{ padding:8px; border-bottom:1px solid #ddd; font-size:13px }
    th{ text-align:left; background:#fafafa }
    tfoot td{ font-weight:700 }
    .note{ margin-top:14px; font-size:12px; color:#333 }
    .footer{ margin-top:18px; border-top:1px solid #000; padding-top:10px; font-size:12px; display:flex; justify-content:space-between; color:#333 }
    .bar{ margin:10px 0 }
    .no-print{ text-align:right; margin-bottom:10px }
    .btn{ padding:8px 12px; border:1px solid #000; background:#000; color:#fff; border-radius:6px; cursor:pointer }
    @media print{ .no-print{ display:none } }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="no-print"><button class="btn" onclick="window.print()">Print</button></div>
    <div class="header">
      <div>
        <div class="brand">S&WALDORF</div>
        <div class="meta">Order: <strong>{{ $order->order_number ?? ('#'.$order->id_order) }}</strong></div>
        <div class="meta">Tanggal: {{ $order->created_at->format('d M Y H:i') }}</div>
      </div>
      <div style="text-align:right">
        <div class="meta">Member: <strong>{{ $order->member->nama_member ?? '-' }}</strong></div>
        <div class="meta">Email: {{ $order->member->email ?? '-' }}</div>
        <div class="meta">No HP: {{ $order->member->no_hp ?? '-' }}</div>
        <div style="margin-top:8px"><span class="badge">{{ str_replace('_',' ', strtoupper($order->status)) }}</span></div>
      </div>
    </div>

    <table>
      <thead>
        <tr>
          <th>Produk</th>
          <th style="width:120px">Harga</th>
          <th style="width:60px">Qty</th>
          <th style="width:140px">Subtotal</th>
        </tr>
      </thead>
      <tbody>
        @foreach($order->items as $it)
        <tr>
          <td>{{ $it->produk->nama_produk ?? ('Produk #'.$it->id_produk) }}</td>
          <td>Rp {{ number_format($it->harga,0,',','.') }}</td>
          <td>{{ $it->qty }}</td>
          <td>Rp {{ number_format($it->subtotal,0,',','.') }}</td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td colspan="3" style="text-align:right">Subtotal</td>
          <td>Rp {{ number_format($order->subtotal ?? $order->total,0,',','.') }}</td>
        </tr>
        <tr>
          <td colspan="3" style="text-align:right">Total</td>
          <td>Rp {{ number_format($order->total,0,',','.') }}</td>
        </tr>
      </tfoot>
    </table>

    <div class="note">
      Tunjukkan struk ini saat pengambilan. Status saat ini: 
      @if($order->status === 'ready_for_pickup')
        <strong>SIAP DIAMBIL</strong>.
      @elseif($order->status === 'awaiting_preparation')
        <strong>SEDANG DIPERSIAPKAN</strong>.
      @elseif($order->status === 'completed')
        <strong>SELESAI (LUNAS DI OUTLET)</strong>.
      @elseif($order->status === 'cancelled')
        <strong>DIBATALKAN</strong>.
      @endif
    </div>

    <div class="footer">
      <div>Metode: Bayar di Outlet (in_store)</div>
      <div>Terima kasih telah berbelanja di S&WALDORF</div>
    </div>
  </div>
</body>
</html>
