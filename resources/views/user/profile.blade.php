@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<style>
    .modern-card {
        border-radius: 18px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        border: none;
    }
    .profile-avatar-wrapper {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, #e60000, #9b2c9b);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3.5rem;
        font-weight: bold;
        box-shadow: 0 8px 20px rgba(230,0,0,0.2);
        margin: -60px auto 20px;
        border: 5px solid white;
    }
    .profile-bg {
        background: url('https://www.transparenttextures.com/patterns/cubes.png'), linear-gradient(135deg, rgba(230,0,0,0.8), rgba(155,44,155,0.8));
        height: 150px;
        border-radius: 18px 18px 0 0;
    }
</style>

<div class="row mb-4 fade-in">
    <div class="col-md-12">
        <div class="page-header py-4" style="background: linear-gradient(135deg, #ffffff 0%, #fefefe 100%); border-radius: 18px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
            <div class="d-flex justify-content-between align-items-center px-4">
                <div>
                    <h4 class="mb-1 fw-bold text-dark"><i class="fas fa-user-circle me-2 text-danger"></i>Profil Pengguna</h4>
                    <p class="text-muted mb-0">Kelola dan lihat informasi akun Anda secara detail</p>
                </div>
                <a href="{{ route('user.dashboard') }}" class="btn btn-light border shadow-sm rounded-pill px-4 fw-medium text-dark">
                    <i class="fas fa-arrow-left me-2"></i> Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center fade-in" style="animation-delay: 0.1s;">
    <div class="col-md-8 col-lg-6">
        <div class="card modern-card overflow-visible">
            <div class="profile-bg"></div>
            <div class="card-body px-5 pb-5 pt-0 text-center">
                <div class="profile-avatar-wrapper">
                    {{ strtoupper(substr(auth()->user()->name ?? $user->name, 0, 1)) }}
                </div>
                
                <h3 class="fw-bold mb-1 text-dark">{{ $user->name }}</h3>
                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3 py-2 mb-4 d-inline-flex align-items-center">
                    <i class="fas fa-id-badge me-2"></i> Peminjam / {{ ucfirst($user->role) }}
                </span>
                
                <div class="bg-light rounded-4 p-4 text-start shadow-sm border border-opacity-10">
                    <h6 class="fw-bold text-secondary text-uppercase mb-4" style="font-size: 0.85rem; letter-spacing: 1px;">
                        Detail Informasi
                    </h6>
                    
                    <div class="row align-items-center mb-4">
                        <div class="col-auto">
                            <div class="bg-white rounded-circle shadow-sm d-flex align-items-center justify-content-center text-primary" style="width: 45px; height: 45px;">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        <div class="col ps-2">
                            <p class="text-muted small mb-0">Nama Lengkap</p>
                            <h6 class="fw-bold text-dark mb-0">{{ $user->name }}</h6>
                        </div>
                    </div>
                    
                    <div class="row align-items-center mb-4">
                        <div class="col-auto">
                            <div class="bg-white rounded-circle shadow-sm d-flex align-items-center justify-content-center text-danger" style="width: 45px; height: 45px;">
                                <i class="fas fa-envelope"></i>
                            </div>
                        </div>
                        <div class="col ps-2">
                            <p class="text-muted small mb-0">Alamat Email</p>
                            <h6 class="fw-bold text-dark mb-0">{{ $user->email }}</h6>
                        </div>
                    </div>
                    
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="bg-white rounded-circle shadow-sm d-flex align-items-center justify-content-center text-success" style="width: 45px; height: 45px;">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                        <div class="col ps-2">
                            <p class="text-muted small mb-0">Terdaftar Pada</p>
                            <h6 class="fw-bold text-dark mb-0">{{ $user->created_at->format('d F Y') }} <span class="text-muted small fw-normal">({{ $user->created_at->diffForHumans() }})</span></h6>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button class="btn btn-outline-danger rounded-pill px-4" onclick="document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i> Log Out
                    </button>
                    <!-- Form Logout -->
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
