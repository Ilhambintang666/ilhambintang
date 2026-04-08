@extends('layouts.guest')

@section('title', 'Login - PMI Inventaris')

@section('content')
<style>
    .bg-image {
        background: linear-gradient(
            rgba(0, 0, 0, 0.4), 
            rgba(0, 0, 0, 0.8)
        ), url("{{ asset('images/pmi.jpeg') }}") no-repeat center center fixed;
        background-size: cover;
        position: relative;
        overflow: hidden;
    }
    
    .bg-image::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        backdrop-filter: blur(5px);
        z-index: 0;
    }

    .login-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        border-radius: 24px;
        z-index: 1;
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    .login-card:hover {
        transform: translateY(-8px);
    }

    .input-group-custom {
        background: #f4f6f9;
        border: 2px solid transparent;
        border-radius: 14px;
        padding: 5px 10px;
        transition: all 0.3s ease;
    }

    .input-group-custom:focus-within {
        background: #ffffff;
        border-color: rgba(230, 0, 0, 0.4);
        box-shadow: 0 0 0 4px rgba(230, 0, 0, 0.1);
    }

    .input-group-custom i {
        color: #adb5bd;
        transition: color 0.3s;
        width: 25px;
        text-align: center;
    }

    .input-group-custom:focus-within i {
        color: #e60000;
    }

    .form-control-custom {
        border: none;
        background: transparent;
        box-shadow: none !important;
        padding: 12px 10px;
        font-weight: 500;
    }

    .form-control-custom::placeholder {
        color: #adb5bd;
        font-weight: 400;
    }

    .btn-login {
        background-color: #e60000;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        letter-spacing: 0.5px;
        transition: all 0.3s;
        padding: 15px;
        color: white;
    }

    .btn-login:hover {
        background-color: #cc0000;
        box-shadow: 0 4px 8px rgba(230, 0, 0, 0.2);
        color: white;
    }

    .register-link {
        color: #e60000;
        text-decoration: none;
        transition: all 0.3s;
        font-weight: 700;
        position: relative;
    }

    .register-link::after {
        content: '';
        position: absolute;
        width: 100%;
        transform: scaleX(0);
        height: 2px;
        bottom: -2px;
        left: 0;
        background-color: #e60000;
        transform-origin: bottom right;
        transition: transform 0.25s ease-out;
    }

    .register-link:hover::after {
        transform: scaleX(1);
        transform-origin: bottom left;
    }

    .logo-container {
        animation: float 6s ease-in-out infinite;
    }
    
    .logo-container img {
        filter: drop-shadow(0 10px 15px rgba(230, 0, 0, 0.2));
    }

    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }
    
    .fade-in-up {
        animation: fadeInUp 0.8s ease-out forwards;
        opacity: 0;
        transform: translateY(20px);
    }
    
    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="d-flex justify-content-center align-items-center min-vh-100 bg-image">
    <div class="card login-card p-4 p-md-5 fade-in-up" style="max-width: 450px; width: 90%;">
        {{-- Logo & Judul --}}
        <div class="text-center mb-4 logo-container">
            <img src="{{ asset('images/pmi.png') }}" alt="PMI Logo" width="80" class="mb-3">
            <h4 class="fw-bold text-dark mb-1">Login Inventaris</h4>
            <p class="text-secondary mb-0 small">PMI Kota Semarang</p>
        </div>
        
        {{-- Form Login --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="mb-4 text-start">
                <label for="email" class="form-label fw-bold text-dark small ms-2 mb-2">Alamat Email</label>
                <div class="input-group-custom d-flex align-items-center px-2">
                    <i class="fa-solid fa-envelope fs-5"></i>
                    <input id="email" type="email" 
                           class="form-control form-control-custom w-100 @error('email') is-invalid @enderror" 
                           name="email" value="{{ old('email') }}" required autofocus placeholder="Masukkan email Anda">
                </div>
                @error('email')
                    <div class="text-danger small mt-2 ms-2 fw-medium"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-5 text-start">
                <label for="password" class="form-label fw-bold text-dark small ms-2 mb-2">Kata Sandi</label>
                <div class="input-group-custom d-flex align-items-center px-2">
                    <i class="fa-solid fa-lock fs-5"></i>
                    <input id="password" type="password" 
                           class="form-control form-control-custom w-100 @error('password') is-invalid @enderror" 
                           name="password" required placeholder="Masukkan kata sandi">
                </div>
                @error('password')
                    <div class="text-danger small mt-2 ms-2 fw-medium"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid mb-3 mt-4">
                <button type="submit" class="btn btn-login fw-bold py-2 shadow-sm">
                    Login
                </button>
            </div>
            
            <div class="text-center mt-2">
                <p class="mb-0 text-muted fw-medium">Belum memiliki akun? 
                    <a href="{{ route('register.peminjam') }}" class="register-link ms-1">
                        Daftar Peminjam
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
