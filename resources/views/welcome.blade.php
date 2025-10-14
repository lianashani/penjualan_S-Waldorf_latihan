<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S&Waldorf - Fashion Retail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary: #000000;
            --secondary: #333333;
            --accent: #666666;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            overflow-x: hidden;
        }
        
        /* Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            padding: 1rem 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            transition: all 0.3s;
        }
        
        .navbar-brand {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary) !important;
        }
        
        .nav-link {
            color: #333 !important;
            font-weight: 500;
            margin: 0 15px;
            transition: color 0.3s;
        }
        
        .nav-link:hover {
            color: var(--secondary) !important;
        }
        
        .btn-login {
            background: var(--primary);
            color: white;
            padding: 8px 25px;
            border-radius: 25px;
            border: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            background: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.3);
        }
        
        /* Hero Section */
        .hero {
            height: 100vh;
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 50%, #333333 100%);
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            margin-top: 70px;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            top: -100px;
            right: -100px;
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .hero-content {
            color: white;
            z-index: 2;
        }
        
        .hero h1 {
            font-size: 64px;
            font-weight: 700;
            margin-bottom: 20px;
            animation: fadeInUp 1s;
        }
        
        .hero p {
            font-size: 24px;
            margin-bottom: 30px;
            animation: fadeInUp 1s 0.2s both;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .btn-hero {
            padding: 15px 40px;
            font-size: 18px;
            border-radius: 30px;
            border: none;
            font-weight: 600;
            margin: 10px;
            transition: all 0.3s;
            animation: fadeInUp 1s 0.4s both;
        }
        
        .btn-primary-hero {
            background: white;
            color: var(--primary);
        }
        
        .btn-primary-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        
        .btn-outline-hero {
            background: transparent;
            color: white;
            border: 2px solid white;
        }
        
        .btn-outline-hero:hover {
            background: white;
            color: var(--primary);
        }
        
        /* Section Styles */
        section {
            padding: 80px 0;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }
        
        .section-title h2 {
            font-size: 42px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 15px;
        }
        
        .section-title p {
            font-size: 18px;
            color: #666;
        }
        
        /* Product Card */
        .product-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            height: 100%;
        }
        
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }
        
        .product-image {
            height: 300px;
            overflow: hidden;
            position: relative;
        }
        
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }
        
        .product-card:hover .product-image img {
            transform: scale(1.1);
        }
        
        .product-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--accent);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .product-body {
            padding: 20px;
        }
        
        .product-category {
            color: #666;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .product-title {
            font-size: 20px;
            font-weight: 600;
            margin: 10px 0;
            color: #333;
        }
        
        .product-price {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
        }
        
        /* Reviews */
        .review-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            height: 100%;
        }
        
        .review-stars {
            color: #fbbf24;
            font-size: 20px;
            margin-bottom: 15px;
        }
        
        .review-text {
            font-style: italic;
            color: #666;
            margin-bottom: 20px;
        }
        
        .reviewer {
            display: flex;
            align-items: center;
        }
        
        .reviewer-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            margin-right: 15px;
        }
        
        /* Contact */
        .contact-section {
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
            color: white;
        }
        
        .contact-info {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 20px;
        }
        
        .contact-info i {
            font-size: 30px;
            margin-bottom: 15px;
        }
        
        .form-control {
            border-radius: 10px;
            padding: 12px;
            border: none;
        }
        
        .btn-submit {
            background: white;
            color: var(--primary);
            padding: 12px 40px;
            border-radius: 25px;
            border: none;
            font-weight: 600;
            width: 100%;
        }
        
        /* Footer */
        footer {
            background: #1a1a1a;
            color: white;
            padding: 50px 0 20px;
        }
        
        .footer-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .footer-link {
            color: #ccc;
            text-decoration: none;
            display: block;
            margin-bottom: 10px;
            transition: color 0.3s;
        }
        
        .footer-link:hover {
            color: white;
        }
        
        .social-icons a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: #000;
            color: white;
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            margin-right: 10px;
            transition: all 0.3s;
            border: 1px solid #333;
        }
        
        .social-icons a:hover {
            background: white;
            color: #000;
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">S&WALDORF</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#new-arrivals">New Arrivals</a></li>
                    <li class="nav-item"><a class="nav-link" href="#catalog">Catalog</a></li>
                    <li class="nav-item"><a class="nav-link" href="#reviews">Reviews</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                    <li class="nav-item ms-3">
                        <a href="{{ route('login') }}" class="btn btn-login">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <h1>Fashion<br>Collection 2025</h1>
                    <p>Discover the latest trends in fashion retail</p>
                    <button class="btn btn-primary-hero">Shop Now</button>
                    <button class="btn btn-outline-hero">View Collection</button>
                </div>
            </div>
        </div>
    </section>

    <!-- New Arrivals -->
    <section id="new-arrivals" style="background: #f8f9fa;">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>New Arrivals</h2>
                <p>Check out our latest products</p>
            </div>
            <div class="row g-4">
                @foreach($newArrivals as $produk)
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="product-card">
                        <div class="product-image">
                            @if($produk->gambar)
                                <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_produk }}">
                            @else
                                <img src="https://via.placeholder.com/300x400/667eea/ffffff?text={{ urlencode($produk->nama_produk) }}" alt="{{ $produk->nama_produk }}">
                            @endif
                            <span class="product-badge">NEW</span>
                        </div>
                        <div class="product-body">
                            <div class="product-category">{{ $produk->kategori->nama_kategori ?? 'Fashion' }}</div>
                            <h5 class="product-title">{{ $produk->nama_produk }}</h5>
                            <div class="product-price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</div>
                            <div class="mt-3 d-grid gap-2">
                                @if(isset($produk->slug) && $produk->slug)
                                    <a href="{{ route('katalog.elegant-detail', $produk->slug) }}" class="btn btn-outline-dark btn-sm">Lihat Detail</a>
                                @else
                                    <a href="{{ route('katalog.elegant') }}" class="btn btn-outline-dark btn-sm">Lihat Detail</a>
                                @endif
                                <a href="{{ route('login') }}" class="btn btn-dark btn-sm">Beli</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section style="background: #f5f5f5;">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Featured Products</h2>
                <p>Our best selling items</p>
            </div>
            <div class="row g-4">
                @foreach($featured as $produk)
                <div class="col-lg-4" data-aos="flip-left" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="product-card">
                        <div class="product-image">
                            @if($produk->gambar)
                                <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_produk }}">
                            @else
                                <img src="https://via.placeholder.com/400x500/f59e0b/ffffff?text={{ urlencode($produk->nama_produk) }}" alt="{{ $produk->nama_produk }}">
                            @endif
                            <span class="product-badge" style="background: #ef4444;">HOT</span>
                        </div>
                        <div class="product-body">
                            <div class="product-category">{{ $produk->kategori->nama_kategori ?? 'Fashion' }}</div>
                            <h5 class="product-title">{{ $produk->nama_produk }}</h5>
                            <p class="text-muted" style="font-size: 14px;">{{ $produk->ukuran }} | {{ $produk->warna }}</p>
                            <div class="product-price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</div>
                            <div class="mt-3 d-grid gap-2">
                                @if(isset($produk->slug) && $produk->slug)
                                    <a href="{{ route('katalog.elegant-detail', $produk->slug) }}" class="btn btn-outline-dark btn-sm">Lihat Detail</a>
                                @else
                                    <a href="{{ route('katalog.elegant') }}" class="btn btn-outline-dark btn-sm">Lihat Detail</a>
                                @endif
                                <a href="{{ route('login') }}" class="btn btn-dark btn-sm">Login untuk membeli</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- All Catalog -->
    <section id="catalog">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Complete Collection</h2>
                <p>Browse all {{ $allProduks->count() }} products in our store</p>
            </div>
            <div class="row g-4">
                @foreach($allProduks as $produk)
                <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="{{ ($loop->index % 8) * 50 }}">
                    <div class="product-card">
                        <div class="product-image">
                            @if($produk->gambar)
                                <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_produk }}">
                            @else
                                <img src="https://via.placeholder.com/300x400/{{ ['000000', '1a1a1a', '333333', '4d4d4d', '666666'][rand(0,4)] }}/ffffff?text={{ urlencode($produk->nama_produk) }}" alt="{{ $produk->nama_produk }}">
                            @endif
                            @if($produk->stok <= 5)
                                <span class="product-badge" style="background: #ef4444;">Only {{ $produk->stok }} left!</span>
                            @endif
                        </div>
                        <div class="product-body">
                            <div class="product-category">{{ $produk->kategori->nama_kategori ?? 'Fashion' }}</div>
                            <h5 class="product-title">{{ $produk->nama_produk }}</h5>
                            <p class="text-muted mb-2" style="font-size: 13px;">
                                <i class="mdi mdi-ruler"></i> {{ $produk->ukuran }} | 
                                <i class="mdi mdi-palette"></i> {{ $produk->warna }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="product-price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</div>
                                <small class="text-muted">Stock: {{ $produk->stok }}</small>
                            </div>
                            <div class="mt-3 d-grid gap-2">
                                @if(isset($produk->slug) && $produk->slug)
                                    <a href="{{ route('katalog.elegant-detail', $produk->slug) }}" class="btn btn-outline-dark btn-sm">Lihat Detail</a>
                                @else
                                    <a href="{{ route('katalog.elegant') }}" class="btn btn-outline-dark btn-sm">Lihat Detail</a>
                                @endif
                                <a href="{{ route('login') }}" class="btn btn-dark btn-sm">Login untuk membeli</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Reviews -->
    <section id="reviews" style="background: #f8f9fa;">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Customer Reviews</h2>
                <p>What our customers say about us</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="0">
                    <div class="review-card">
                        <div class="review-stars">
                            <i class="mdi mdi-star"></i>
                            <i class="mdi mdi-star"></i>
                            <i class="mdi mdi-star"></i>
                            <i class="mdi mdi-star"></i>
                            <i class="mdi mdi-star"></i>
                        </div>
                        <p class="review-text">"Produk berkualitas tinggi dengan harga terjangkau. Sangat puas dengan pelayanan S&Waldorf!"</p>
                        <div class="reviewer">
                            <div class="reviewer-avatar">L</div>
                            <div>
                                <strong>Liana</strong><br>
                                <small class="text-muted">Verified Customer</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="review-card">
                        <div class="review-stars">
                            <i class="mdi mdi-star"></i>
                            <i class="mdi mdi-star"></i>
                            <i class="mdi mdi-star"></i>
                            <i class="mdi mdi-star"></i>
                            <i class="mdi mdi-star"></i>
                        </div>
                        <p class="review-text">"Koleksi fashion terlengkap! Selalu update dengan trend terbaru. Recommended!"</p>
                        <div class="reviewer">
                            <div class="reviewer-avatar">S</div>
                            <div>
                                <strong>Shani</strong><br>
                                <small class="text-muted">Verified Customer</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="review-card">
                        <div class="review-stars">
                            <i class="mdi mdi-star"></i>
                            <i class="mdi mdi-star"></i>
                            <i class="mdi mdi-star"></i>
                            <i class="mdi mdi-star"></i>
                            <i class="mdi mdi-star-half-full"></i>
                        </div>
                        <p class="review-text">"Pengalaman belanja yang menyenangkan. Staff ramah dan helpful. Will come back!"</p>
                        <div class="reviewer">
                            <div class="reviewer-avatar">M</div>
                            <div>
                                <strong>Member</strong><br>
                                <small class="text-muted">Verified Customer</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact -->
    <section id="contact" class="contact-section">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2 style="color: white;">Get In Touch</h2>
                <p style="color: rgba(255,255,255,0.8);">We'd love to hear from you</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4" data-aos="fade-right">
                    <div class="contact-info text-center">
                        <i class="mdi mdi-map-marker"></i>
                        <h5>Address</h5>
                        <p>Jl. Fashion Street No. 24<br>Bandung Marhas, Indonesia</p>
                    </div>
                    <div class="contact-info text-center">
                        <i class="mdi mdi-phone"></i>
                        <h5>Phone</h5>
                        <p>(021) 98765432</p>
                    </div>
                    <div class="contact-info text-center">
                        <i class="mdi mdi-email"></i>
                        <h5>Email</h5>
                        <p>info@swaldorf.com</p>
                    </div>
                </div>
                <div class="col-lg-8" data-aos="fade-left">
                    <form class="bg-white p-4 rounded">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Your Name" required>
                            </div>
                            <div class="col-md-6">
                                <input type="email" class="form-control" placeholder="Your Email" required>
                            </div>
                            <div class="col-12">
                                <input type="text" class="form-control" placeholder="Subject" required>
                            </div>
                            <div class="col-12">
                                <textarea class="form-control" rows="5" placeholder="Your Message" required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-submit">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h3 class="footer-title">S&WALDORF</h3>
                    <p>Your trusted fashion retail partner. Quality products, affordable prices.</p>
                    <div class="social-icons mt-3">
                        <a href="#"><i class="mdi mdi-facebook"></i></a>
                        <a href="#"><i class="mdi mdi-instagram"></i></a>
                        <a href="#"><i class="mdi mdi-twitter"></i></a>
                        <a href="#"><i class="mdi mdi-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-2">
                    <h5 class="footer-title">Quick Links</h5>
                    <a href="#home" class="footer-link">Home</a>
                    <a href="#new-arrivals" class="footer-link">New Arrivals</a>
                    <a href="#catalog" class="footer-link">Catalog</a>
                    <a href="#reviews" class="footer-link">Reviews</a>
                </div>
                <div class="col-lg-2">
                    <h5 class="footer-title">Customer</h5>
                    <a href="{{ route('member.login') }}" class="footer-link">Member Login</a>
                    <a href="{{ route('login') }}" class="footer-link">Staff Login</a>
                    <a href="#contact" class="footer-link">Contact Us</a>
                </div>
                <div class="col-lg-4">
                    <h5 class="footer-title">Store Hours</h5>
                    <p>Monday - Friday: 09:00 - 21:00<br>
                    Saturday: 10:00 - 22:00<br>
                    Sunday: 10:00 - 20:00</p>
                </div>
            </div>
            <hr style="border-color: rgba(255,255,255,0.1); margin: 30px 0;">
            <div class="text-center">
                <p>&copy; 2025 S&Waldorf. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.boxShadow = '0 5px 30px rgba(0,0,0,0.15)';
            } else {
                navbar.style.boxShadow = '0 2px 20px rgba(0,0,0,0.1)';
            }
        });
    </script>
</body>
</html>
