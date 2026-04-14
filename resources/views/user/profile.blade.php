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
    <div class="col-md-10 col-lg-8">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4 px-4 py-3" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle me-3 fs-4"></i>
                    <div>
                        <strong class="d-block">Berhasil!</strong>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4 px-4 py-3" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-circle me-3 fs-4"></i>
                    <div>
                        <strong class="d-block">Gagal!</strong>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4 px-4 py-3" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-times-circle me-3 fs-4"></i>
                    <ul class="mb-0 ps-0 list-unstyled">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card modern-card overflow-visible mb-4">
            <div class="profile-bg"></div>
            <div class="card-body px-4 px-md-5 pb-5 pt-0 text-center">
                
                <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
                    @csrf
                    <div class="profile-avatar-wrapper position-relative" style="cursor: pointer;" onclick="document.getElementById('avatarInput').click();">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" id="avatarPreview" alt="Avatar" class="rounded-circle w-100 h-100" style="object-fit: cover;">
                        @else
                            <div id="avatarInitial">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                            <img src="" id="avatarPreview" alt="Avatar" class="rounded-circle w-100 h-100 d-none" style="object-fit: cover;">
                        @endif
                        <div class="position-absolute bottom-0 end-0 bg-white shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border: 2px solid white;">
                            <i class="fas fa-camera text-primary small"></i>
                        </div>
                    </div>
                    <input type="file" name="avatar" id="avatarInput" class="d-none" accept="image/*" onchange="previewAvatar(this)">
                    
                    <h3 class="fw-bold mb-1 text-dark">{{ $user->name }}</h3>
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3 py-2 mb-4">
                        <i class="fas fa-id-badge me-2"></i> Peminjam / {{ ucfirst($user->role) }}
                    </span>

                    <div class="bg-light rounded-4 p-4 text-start shadow-sm border border-opacity-10 mb-4">
                        <h6 class="fw-bold text-secondary text-uppercase mb-4" style="font-size: 0.85rem; letter-spacing: 1px;">
                            Informasi Akun
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Nama Lengkap</label>
                                <div class="input-group shadow-sm rounded-3 overflow-hidden">
                                    <span class="input-group-text bg-white border-0"><i class="fas fa-user text-primary"></i></span>
                                    <input type="text" name="name" class="form-control border-0 py-2.5" value="{{ old('name', $user->name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Alamat Email</label>
                                <div class="input-group shadow-sm rounded-3 overflow-hidden">
                                    <span class="input-group-text bg-white border-0"><i class="fas fa-envelope text-danger"></i></span>
                                    <input type="email" name="email" class="form-control border-0 py-2.5" value="{{ old('email', $user->email) }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 text-center">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>

                <div class="bg-light rounded-4 p-4 text-start shadow-sm border border-opacity-10">
                    <h6 class="fw-bold text-secondary text-uppercase mb-4" style="font-size: 0.85rem; letter-spacing: 1px;">
                        Keamanan & Akun
                    </h6>
                    
                    <button class="btn btn-white bg-white w-100 d-flex align-items-center justify-content-between p-3 rounded-3 shadow-sm border-0 mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#passwordForm">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="fas fa-lock"></i>
                            </div>
                            <div>
                                <strong class="d-block text-dark">Ganti Password</strong>
                                <span class="text-muted small">Perbarui kata sandi akun Anda secara berkala</span>
                            </div>
                        </div>
                        <i class="fas fa-chevron-down text-muted"></i>
                    </button>

                    <div class="collapse mb-3" id="passwordForm">
                        <form action="{{ route('user.profile.password') }}" method="POST" class="bg-white p-4 rounded-3 border">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Password Saat Ini</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Password Baru</label>
                                <input type="password" name="new_password" class="form-control" required minlength="8">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Konfirmasi Password Baru</label>
                                <input type="password" name="new_password_confirmation" class="form-control" required minlength="8">
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-warning fw-bold px-4 rounded-pill">Update Password</button>
                            </div>
                        </form>
                    </div>

                    <div class="row align-items-center px-2">
                        <div class="col-auto">
                            <div class="bg-white rounded-circle shadow-sm d-flex align-items-center justify-content-center text-success" style="width: 40px; height: 40px;">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                        <div class="col ps-2">
                            <p class="text-muted small mb-0">Terdaftar Pada</p>
                            <h6 class="fw-bold text-dark mb-0">{{ $user->created_at->format('d F Y') }} <span class="text-muted small fw-normal">({{ $user->created_at->diffForHumans() }})</span></h6>
                        </div>
                    </div>
                </div>
                
                <div class="mt-5 pt-3 border-top">
                    <button class="btn btn-outline-danger border-0 rounded-pill px-4 fw-bold" onclick="if(confirm('Apakah Anda yakin ingin keluar?')) document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i> Log Out Akun
                    </button>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                var preview = document.getElementById('avatarPreview');
                var initial = document.getElementById('avatarInitial');
                
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                if (initial) initial.classList.add('d-none');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
