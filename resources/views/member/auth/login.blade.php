<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login - S&Waldorf</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon.png') }}">
    
    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/libs/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    
    <!-- Material Design Icons -->
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-wrapper {
            display: flex;
            width: 100%;
            max-width: 750px;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.08);
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Left Side - Form */
        .login-form-section {
            flex: 1;
            padding: 32px 32px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* Right Side - Image/Illustration */
        .login-image-section {
            flex: 1;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            padding: 32px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .login-image-section::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -100px;
            right: -100px;
        }

        .login-image-section::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            bottom: -80px;
            left: -80px;
        }

        .illustration-content {
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .illustration-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .illustration-icon i {
            font-size: 30px;
            color: white;
        }

        .illustration-content h2 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 10px;
            line-height: 1.3;
        }

        .illustration-content p {
            font-size: 13px;
            opacity: 0.9;
            line-height: 1.4;
            max-width: 260px;
            margin: 0 auto;
        }

        /* Logo Section */
        .logo-section {
            margin-bottom: 24px;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 4px;
        }

        .logo-icon-small {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-icon-small i {
            font-size: 18px;
            color: white;
        }

        .brand-name {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
        }

        .brand-tagline {
            color: #64748b;
            font-size: 12px;
            margin-left: 40px;
        }

        /* Welcome Text */
        .welcome-text {
            margin-bottom: 24px;
        }

        .welcome-text h1 {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .welcome-text p {
            color: #64748b;
            font-size: 13px;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            font-weight: 500;
            color: #334155;
            margin-bottom: 6px;
            font-size: 13px;
            display: block;
        }

        .form-control-custom {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 13px;
            transition: all 0.2s ease;
            background: white;
            color: #1e293b;
        }

        .form-control-custom:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-control-custom::placeholder {
            color: #94a3b8;
        }

        .form-control-custom.is-invalid {
            border-color: #ef4444;
        }

        .invalid-feedback {
            color: #ef4444;
            font-size: 13px;
            margin-top: 6px;
            display: block;
        }

        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #94a3b8;
            font-size: 18px;
            transition: color 0.2s;
        }

        .password-toggle:hover {
            color: #3b82f6;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .custom-checkbox {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .custom-checkbox input[type="checkbox"] {
            width: 16px;
            height: 16px;
            margin-right: 8px;
            cursor: pointer;
            accent-color: #3b82f6;
        }

        .custom-checkbox label {
            font-size: 14px;
            color: #64748b;
            cursor: pointer;
            margin: 0;
        }

        .btn-login {
            width: 100%;
            padding: 11px;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(59, 130, 246, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .divider {
            text-align: center;
            margin: 24px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: #e2e8f0;
        }

        .divider span {
            background: white;
            padding: 0 12px;
            color: #94a3b8;
            font-size: 13px;
            position: relative;
            z-index: 1;
        }

        .demo-accounts {
            background: #f8fafc;
            border-radius: 10px;
            padding: 12px;
            border: 1px solid #e2e8f0;
        }

        .demo-accounts h4 {
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .demo-accounts h4 i {
            margin-right: 5px;
            color: #3b82f6;
            font-size: 14px;
        }

        .demo-item {
            background: white;
            padding: 8px 10px;
            border-radius: 6px;
            margin-bottom: 6px;
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
        }

        .demo-item:hover {
            border-color: #3b82f6;
            transform: translateX(4px);
        }

        .demo-item:last-child {
            margin-bottom: 0;
        }

        .demo-role {
            font-weight: 600;
            color: #1e293b;
            font-size: 13px;
            margin-bottom: 3px;
        }

        .demo-credentials {
            font-size: 12px;
            color: #64748b;
            font-family: 'Courier New', monospace;
        }

        .alert-custom {
            padding: 12px 14px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
            display: flex;
            align-items: center;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-custom i {
            margin-right: 8px;
            font-size: 16px;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert-warning {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        /* Responsive */
        @media (max-width: 968px) {
            .login-image-section {
                display: none;
            }
            
            .login-form-section {
                max-width: 500px;
                margin: 0 auto;
            }
        }

        @media (max-width: 576px) {
            .login-form-section {
                padding: 40px 30px;
            }

            .welcome-text h1 {
                font-size: 24px;
            }
        }

        /* Loading Animation */
        .btn-login.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .btn-login.loading::after {
            content: '';
            position: absolute;
            width: 18px;
            height: 18px;
            top: 50%;
            left: 50%;
            margin-left: -9px;
            margin-top: -9px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .features-list {
            margin-top: 24px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            color: white;
        }

        .feature-icon {
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            flex-shrink: 0;
        }

        .feature-icon i {
            font-size: 16px;
        }

        .feature-text h4 {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .feature-text p {
            font-size: 11px;
            opacity: 0.8;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <!-- Left Side - Login Form -->
        <div class="login-form-section">
            <!-- Logo -->
            <div class="logo-section">
                <div class="logo text-center">
                    <h2 class="mb-0"><b>S&WALDORF</b></h2>
                    <p class="text-muted">Retail Fashion Management System</p>
                    <span class="badge badge-success badge-pill">Member Login</span>
                    <p class="mt-3"><small>Admin/Kasir? <a href="{{ route('login') }}">Login disini</a></small></p>
                </div>
            </div>

            <!-- Welcome Text -->
            <div class="welcome-text">
                <h1>Welcome Back</h1>
                <p>Silakan login untuk mengakses dashboard</p>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
            <div class="alert-custom alert-success">
                <i class="mdi mdi-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="alert-custom alert-error">
                <i class="mdi mdi-alert-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            @if(session('warning'))
            <div class="alert-custom alert-warning">
                <i class="mdi mdi-alert"></i>
                <span>{{ session('warning') }}</span>
            </div>
            @endif

            @if($errors->any())
            <div class="alert-custom alert-error">
                <i class="mdi mdi-alert-circle"></i>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('member.login') }}" class="login-form" id="loginForm">
                @csrf
                
                <!-- Email -->
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" 
                           name="email" 
                           class="form-control-custom @error('email') is-invalid @enderror" 
                           placeholder="nama@email.com"
                           value="{{ old('email') }}"
                           required
                           autofocus>
                    @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="password-wrapper">
                        <input type="password" 
                               name="password" 
                               id="password"
                               class="form-control-custom @error('password') is-invalid @enderror" 
                               placeholder="Masukkan password"
                               required>
                        <i class="mdi mdi-eye-outline password-toggle" id="togglePassword"></i>
                    </div>
                    @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="remember-forgot">
                    <div class="custom-checkbox">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Remember me</label>
                    </div>
                </div>

                <!-- Login Button -->
                <button type="submit" class="btn-login" id="btnLogin">
                    <span>Sign In</span>
                </button>
            </form>
        </div>

        <!-- Right Side - Illustration -->
        <div class="login-image-section">
            <div class="illustration-content">
                <div class="illustration-icon">
                    <i class="mdi mdi-account-circle" style="font-size: 80px; margin-bottom: 20px;"></i>
                </div>
                <h2>Member Area</h2>
                <p class="mt-3">Belanja Fashion & Dapatkan Poin Reward</p>
                <p>Platform terpadu untuk mengelola penjualan, inventori, dan pelanggan dengan mudah dan efisien</p>
                
                <div class="features-list">
                    <div class="feature-item">
                        <div class="feature-icon">
                        </div>
                        <div class="feature-text">
                            <h4>Secure & Reliable</h4>
                            <p>Keamanan data terjamin</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="mdi mdi-chart-line"></i>
                        </div>
                        <div class="feature-text">
                            <h4>Real-time Analytics</h4>
                            <p>Laporan penjualan real-time</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="mdi mdi-account-multiple"></i>
                        </div>
                        <div class="feature-text">
                            <h4>Multi-user Access</h4>
                            <p>Role-based permissions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Toggle Password Visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle icon
            this.classList.toggle('mdi-eye-outline');
            this.classList.toggle('mdi-eye-off-outline');
        });

        // Form Submit Loading
        const loginForm = document.getElementById('loginForm');
        const btnLogin = document.getElementById('btnLogin');

        loginForm.addEventListener('submit', function() {
            btnLogin.classList.add('loading');
            btnLogin.querySelector('span').textContent = 'Memproses...';
        });

        // Auto dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-custom');
            alerts.forEach(alert => {
                alert.style.animation = 'slideUp 0.4s ease-out reverse';
                setTimeout(() => alert.remove(), 400);
            });
        }, 5000);
    </script>
</body>
</html>
