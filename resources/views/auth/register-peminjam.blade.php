@extends('layouts.guest')

@section('title', 'Daftar Peminjam - PMI Inventaris')

@section('content')
<style>
    .bg-image {
        background: 
            linear-gradient(135deg, rgba(230, 0, 0, 0.75) 0%, rgba(155, 44, 155, 0.85) 100%),
            url('{{ asset('images/pmi.jpeg') }}') no-repeat center center;
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

    .register-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        border-radius: 24px;
        z-index: 1;
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    .register-card:hover {
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

    .btn-register {
        background: linear-gradient(135deg, #e60000 0%, #ff4b2b 100%);
        border: none;
        border-radius: 14px;
        font-weight: 700;
        letter-spacing: 0.5px;
        transition: all 0.3s;
        padding: 15px;
        color: white;
    }

    .btn-register:hover {
        background: linear-gradient(135deg, #cc0000 0%, #e60000 100%);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(230, 0, 0, 0.25);
        color: white;
    }

    .login-link {
        color: #e60000;
        text-decoration: none;
        transition: all 0.3s;
        font-weight: 700;
        position: relative;
    }

    .login-link::after {
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

    .login-link:hover::after {
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

<div class="d-flex justify-content-center align-items-center min-vh-100 bg-image py-5">
    <div class="card register-card p-4 p-md-5 fade-in-up" style="max-width: 500px; width: 90%;">
        {{-- Logo & Judul --}}
        <div class="text-center mb-4 logo-container">
            <img src="{{ asset('images/pmi.png') }}" alt="PMI Logo" width="90" class="mb-3">
            <h3 class="fw-bold text-dark mb-1" style="letter-spacing: -0.5px;">Daftar Peminjam</h3>
            <p class="text-secondary mb-0 fw-medium">Lengkapi data untuk mengakses sistem</p>
        </div>
        
        @if ($errors->any())
            <div class="alert alert-danger border-0 rounded-3 shadow-sm mb-4 small fw-medium">
                <i class="fas fa-exclamation-triangle me-2"></i> Terdapat kesalahan:
                <ul class="mb-0 mt-2 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Registrasi --}}
        <form method="POST" action="{{ route('register.peminjam.submit') }}">
            @csrf
            
            <div class="mb-4 text-start">
                <label class="form-label fw-bold text-dark small ms-2 mb-2">Nama Lengkap</label>
                <div class="input-group-custom d-flex align-items-center px-2">
                    <i class="fa-solid fa-user fs-5"></i>
                    <input type="text" name="name" 
                           class="form-control form-control-custom w-100" 
                           value="{{ old('name') }}" required autofocus placeholder="Masukkan nama lengkap Anda">
                </div>
            </div>

            <div class="mb-4 text-start">
                <label class="form-label fw-bold text-dark small ms-2 mb-2">Alamat Email</label>
                <div class="input-group-custom d-flex align-items-center px-2">
                    <i class="fa-solid fa-envelope fs-5"></i>
                    <input type="email" name="email" 
                           class="form-control form-control-custom w-100" 
                           value="{{ old('email') }}" required placeholder="Masukkan email aktif Anda">
                </div>
            </div>

            <div class="mb-5 text-start">
                <label class="form-label fw-bold text-dark small ms-2 mb-2">Kata Sandi</label>
                <div class="input-group-custom d-flex align-items-center px-2">
                    <i class="fa-solid fa-lock fs-5"></i>
                    <input type="password" name="password" 
                           class="form-control form-control-custom w-100" 
                           required placeholder="Buat kata sandi (min. 6 karakter)">
                </div>
            </div>

            <div class="d-grid mb-4">
                <button type="submit" class="btn btn-register shadow-sm d-flex justify-content-center align-items-center">
                    DAFTAR SEKARANG <i class="fa-solid fa-user-plus ms-2"></i>
                </button>
            </div>
            
            <div class="text-center mt-2">
                <p class="mb-0 text-muted fw-medium">Sudah memiliki akun? 
                    <a href="{{ route('login') }}" class="login-link ms-1">
                        Masuk disini
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
