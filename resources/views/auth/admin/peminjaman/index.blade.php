@extends('layouts.app')

@section('title', 'Verifikasi Peminjaman')

@section('content')
<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h3 class="mb-1">
            <i class="fas fa-clipboard-check me-2 text-danger"></i>
            Verifikasi Peminjaman
        </h3>
        <p class="text-muted mb-0 small">Kelola dan verifikasi permintaan peminjaman barang</p>
    </div>
    <div class="text-end">
        <span class="badge bg-danger fs-6 rounded-pill">
            <i class="fas fa-clock me-1"></i>
            {{ $loans->count() }} Menunggu
        </span>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #e60000, #9b2c9b); color: white;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1 text-white">{{ $loans->where('status', 'pending')->count() }}</h2>
                        <p class="mb-0 opacity-75 small">Menunggu Persetujuan</p>
                    </div>
                    <i class="fas fa-hourglass-half fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #4facfe, #00f2fe); color: white;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1 text-white">{{ \App\Models\Loan::where('status', 'approved')->count() }}</h2>
                        <p class="mb-0 opacity-75 small">Total Peminjaman Berjalan</p>
                    </div>
                    <i class="fas fa-clipboard-list fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

@if($loans->isEmpty())
    <!-- Empty State -->
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#e60000,#9b2c9b);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                <i class="fas fa-check-circle fa-2x text-white"></i>
            </div>
            <h4 class="text-muted">Semua Pengajuan Pinjaman Terverifikasi!</h4>
            <p class="text-muted small mb-4">Tidak ada pengajuan peminjaman baru saat ini.</p>
        </div>
    </div>
@else
    <!-- Loan Cards -->
    <div class="row">
        @foreach($loans as $loan)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 loan-card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-warning text-dark">
                            Menunggu Persetujuan
                        </span>
                        <small class="text-muted">
                            <i class="far fa-calendar-alt me-1"></i>
                            {{ $loan->created_at->format('d M Y') }}
                        </small>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Borrower Info -->
                    <div class="d-flex align-items-center mb-3">
                        <div class="user-avatar-sm me-3" style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#e60000,#9b2c9b);display:flex;align-items:center;justify-content:center;color:white;font-weight:bold;">
                            {{ strtoupper(substr($loan->user->name ?? 'A', 0, 1)) }}
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">{{ $loan->user->name ?? 'N/A' }}</h6>
                            <small class="text-muted">{{ $loan->user->email ?? 'Peminjam' }}</small>
                        </div>
                    </div>
                    
                    <!-- Item Info -->
                    <div class="d-flex align-items-center mb-3">
                        <div class="user-avatar-sm me-3" style="width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg,#11998e,#38ef7d);display:flex;align-items:center;justify-content:center;color:white;">
                            <i class="fas fa-box"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">{{ $loan->item->name ?? 'N/A' }}</h6>
                            <small class="text-muted">
                                <i class="fas fa-barcode me-1"></i>
                                <code class="bg-light px-1">{{ $loan->item->barcode ?? 'N/A' }}</code>
                            </small>
                        </div>
                    </div>
                    
                    <!-- Loan Details -->
                    <div class="bg-light rounded p-3 mb-3 border-start border-3 border-danger">
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted d-block">Tgl Pinjam</small>
                                <strong>{{ \Carbon\Carbon::parse($loan->borrow_date)->format('d M Y') }}</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Tgl Kembali</small>
                                <strong>{{ \Carbon\Carbon::parse($loan->expected_return_date)->format('d M Y') }}</strong>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notes -->
                    @if($loan->notes)
                    <div class="mb-2 p-2 rounded" style="background:rgba(255,193,7,0.1);">
                        <small class="text-muted d-block"><i class="fas fa-sticky-note me-1"></i>Catatan Pengajuan:</small>
                        <p class="mb-0 small fw-medium">{{ $loan->notes }}</p>
                    </div>
                    @endif
                </div>
                <div class="card-footer bg-white border-0 pb-3">
                    <div class="d-flex gap-2">
                        <form action="{{ route('admin.peminjaman.approve', $loan->id) }}" method="POST" class="w-100">
                            @csrf
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Setujui pinjaman ini?')">
                                <i class="fas fa-check me-1"></i>Setuju
                            </button>
                        </form>
                        <form action="{{ route('admin.peminjaman.reject', $loan->id) }}" method="POST" class="w-100">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Tolak pinjaman ini?')">
                                <i class="fas fa-times me-1"></i>Tolak
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif

<style>
    .loan-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .loan-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
</style>
@endsection
