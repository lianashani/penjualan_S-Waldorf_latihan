<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# S-Waldorf - Sistem Penjualan

Sistem penjualan dengan fitur member checkout dan integrasi Midtrans payment gateway.

## 🚀 Fitur Terbaru: Midtrans Payment Gateway

Member sekarang bisa checkout dengan pembayaran online menggunakan Midtrans!

### Metode Pembayaran yang Tersedia:
- ✅ Transfer Bank (Virtual Account): BCA, BNI, BRI, Mandiri, Permata
- ✅ E-Wallet: GoPay, ShopeePay
- ✅ Kartu Kredit/Debit
- ✅ Bayar di Outlet (Cash on Pickup)

### Setup Midtrans (5 Menit):

1. **Install Dependencies** (Sudah dilakukan ✅)
   ```bash
   composer require midtrans/midtrans-php
   ```

2. **Tambahkan ke .env** (Copy dari `.env.midtrans`)
   ```env
   MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxxx
   MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxxxx
   MIDTRANS_IS_PRODUCTION=false
   ```

3. **Dapatkan Credentials:**
   - Daftar di https://dashboard.sandbox.midtrans.com/
   - Settings → Access Keys
   - Copy Server Key & Client Key

4. **Setup Notification URL:**
   - Settings → Configuration
   - Payment Notification URL: `https://your-domain.com/midtrans/notification`
   - Untuk local: gunakan [ngrok](https://ngrok.com/) 

5. **Test!**
   - Login sebagai member
   - Tambah produk ke cart
   - Pilih "Bayar Online (Midtrans)"
   - Test card: `4811 1111 1111 1114`

📖 **Dokumentasi Lengkap:** [MIDTRANS_SETUP.md](MIDTRANS_SETUP.md)

---

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
