@extends('layouts.auth')

@section('title', 'Member Login')

@section('content')
<div class="login-wrapper">
    <!-- Left Side - Login Form -->
    <div class="login-form-section">
        <!-- Logo -->
        <div class="logo-section">
            <div class="logo text-center">
                <h2 class="mb-0"><b>S&WALDORF</b></h2>
                <p class="text-muted">Retail Fashion Management System</p>
                <span class="badge badge-success badge-pill">Member Area</span>
                <p class="mt-3"><small>Admin/Kasir? <a href="{{ route('login') }}">Login disini</a></small></p>
            </div>
        </div>

        <!-- Welcome Text -->
        <div class="welcome-text">
            <h1>Welcome Back</h1>
            <p>Silakan login sebagai member</p>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="mdi mdi-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="mdi mdi-alert-circle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('member.login') }}" class="login-form">
            @csrf
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="mdi mdi-email"></i></span>
                    </div>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" placeholder="member@example.com" 
                           value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="mdi mdi-lock"></i></span>
                    </div>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           id="password" name="password" placeholder="••••••••" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="remember" name="remember">
                    <label class="custom-control-label" for="remember">Remember Me</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-login">
                <i class="mdi mdi-login"></i> Login
            </button>

            <div class="text-center mt-3">
                <p>Belum punya akun? <a href="{{ route('member.register') }}" class="text-primary"><strong>Daftar Sekarang</strong></a></p>
            </div>
        </form>
    </div>

    <!-- Right Side - Illustration -->
    <div class="login-image-section">
        <div class="illustration-content">
            <i class="mdi mdi-account-circle" style="font-size: 120px; opacity: 0.9;"></i>
            <h2 class="mt-4">Member S&Waldorf</h2>
            <p class="mt-3">Belanja fashion terlengkap dengan sistem poin reward</p>
            <ul class="feature-list">
                <li><i class="mdi mdi-check-circle"></i> Dapatkan poin setiap pembelian</li>
                <li><i class="mdi mdi-check-circle"></i> Tukar poin jadi diskon</li>
                <li><i class="mdi mdi-check-circle"></i> Akses katalog eksklusif</li>
            </ul>
        </div>
    </div>
</div>
@endsection
