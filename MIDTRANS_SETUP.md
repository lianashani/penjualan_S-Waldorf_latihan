# MIDTRANS PAYMENT GATEWAY SETUP GUIDE

## Instalasi Midtrans untuk S-Waldorf

### 1. Konfigurasi Credentials

Tambahkan konfigurasi berikut ke file `.env` Anda:

```env
# Midtrans Configuration (Sandbox)
MIDTRANS_SERVER_KEY=your-server-key-here
MIDTRANS_CLIENT_KEY=your-client-key-here
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

### 2. Mendapatkan Credentials Midtrans

1. **Daftar/Login ke Midtrans Dashboard:**
   - Sandbox: https://dashboard.sandbox.midtrans.com/
   - Production: https://dashboard.midtrans.com/

2. **Ambil Server Key dan Client Key:**
   - Login ke dashboard Midtrans
   - Pilih menu **Settings → Access Keys**
   - Copy **Server Key** dan **Client Key** (Sandbox)
   - Paste ke file `.env` Anda

### 3. Setup Notification URL di Midtrans Dashboard

1. Login ke Midtrans Dashboard (Sandbox)
2. Pilih menu **Settings → Configuration**
3. Set **Payment Notification URL** ke:
   ```
   https://your-domain.com/midtrans/notification
   ```
   Untuk development local, gunakan ngrok:
   ```
   https://your-ngrok-url.ngrok.io/midtrans/notification
   ```

4. Set **Finish Redirect URL** (optional):
   ```
   https://your-domain.com/member/payment/finish
   ```

### 4. Testing dengan Ngrok (Development)

Karena Midtrans perlu mengirim notification ke server Anda, gunakan ngrok untuk expose local server:

```bash
# Install ngrok dari https://ngrok.com/

# Jalankan Laravel server
php artisan serve

# Di terminal lain, jalankan ngrok
ngrok http 8000

# Copy HTTPS URL dari ngrok (contoh: https://abc123.ngrok.io)
# Update Notification URL di Midtrans Dashboard dengan URL ngrok
```

### 5. Test Card Numbers (Sandbox)

Untuk testing di sandbox, gunakan card number berikut:

**Success Transaction:**
- Card: 4811 1111 1111 1114
- CVV: 123
- Exp: 01/25

**Failed Transaction:**
- Card: 4911 1111 1111 1113
- CVV: 123
- Exp: 01/25

**Challenge by FDS:**
- Card: 4411 1111 1111 1118
- CVV: 123
- Exp: 01/25

### 6. Virtual Account Testing

**BCA Virtual Account:**
- Pilih BCA VA di Snap
- Akan generate nomor VA
- Untuk simulate payment, gunakan simulator di dashboard

**Other Banks:**
- Mandiri Bill: Kode Perusahaan + nomor VA
- BNI VA: Nomor yang di-generate
- BRI VA: Nomor yang di-generate

### 7. E-Wallet Testing

**GoPay:**
- Gunakan GoPay simulator
- Status akan otomatis success setelah beberapa detik

**ShopeePay:**
- Sama seperti GoPay, gunakan simulator

### 8. Flow Pembayaran

1. Member menambahkan produk ke keranjang
2. Di halaman keranjang, pilih "Bayar Online (Midtrans)"
3. Klik tombol "Bayar Sekarang"
4. Popup Midtrans Snap akan muncul
5. Pilih metode pembayaran (VA/E-wallet/Card)
6. Complete pembayaran
7. Midtrans akan kirim notification ke endpoint `/midtrans/notification`
8. Status order akan terupdate otomatis
9. Member akan diredirect ke halaman order

### 9. Status Flow

**Payment Status:**
- `pending` - Menunggu pembayaran
- `paid` - Pembayaran berhasil
- `failed` - Pembayaran gagal
- `expired` - Pembayaran expired

**Order Status:**
- `pending` - Order dibuat, menunggu pembayaran
- `awaiting_preparation` - Pembayaran sukses, menunggu diproses
- `ready_for_pickup` - Siap diambil
- `completed` - Selesai
- `cancelled` - Dibatalkan

### 10. Troubleshooting

**Error: Snap token tidak muncul**
- Cek MIDTRANS_SERVER_KEY dan MIDTRANS_CLIENT_KEY di .env
- Pastikan menggunakan sandbox key untuk testing

**Notification tidak diterima**
- Pastikan notification URL sudah di-set di Midtrans Dashboard
- Untuk local development, gunakan ngrok
- Cek log di `storage/logs/laravel.log`

**Payment stuck di pending**
- Cek apakah notification endpoint bisa diakses dari internet
- Pastikan route `/midtrans/notification` tidak menggunakan CSRF middleware

### 11. Production Deployment

Untuk production:
1. Ganti ke Production credentials di .env:
   ```env
   MIDTRANS_SERVER_KEY=your-production-server-key
   MIDTRANS_CLIENT_KEY=your-production-client-key
   MIDTRANS_IS_PRODUCTION=true
   ```

2. Update Snap script di view dari sandbox ke production:
   ```html
   <!-- Production -->
   <script src="https://app.midtrans.com/snap/snap.js"></script>
   ```

3. Submit merchant account verification di Midtrans

### 12. Monitoring

- Login ke Midtrans Dashboard untuk monitoring transaksi
- Cek menu **Transactions** untuk melihat semua pembayaran
- Export report dari dashboard untuk reconciliation

---

## Support

- Midtrans Documentation: https://docs.midtrans.com/
- Midtrans Snap Documentation: https://snap-docs.midtrans.com/
- Support: support@midtrans.com
