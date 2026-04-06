@extends('layouts.app')

@section('title', 'Verifikasi Pengembalian')

@section('content')
<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">
            <i class="fas fa-clipboard-list me-2 text-primary"></i>
            Verifikasi Pengembalian
        </h3>
        <p class="text-muted mb-0 small">Periksa foto bukti dan setujui pengembalian barang dari peminjam</p>
    </div>
    <div class="text-end">
        <span class="badge bg-primary fs-6 rounded-pill">
            <i class="fas fa-clock me-1"></i>
            {{ $loans->count() }} Menunggu Konfirmasi
        </span>
    </div>
</div>

@if($loans->isEmpty())
    <!-- Empty State -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body text-center py-5">
            <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#0052D4,#4364F7,#6FB1FC);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                <i class="fas fa-check-double fa-2x text-white"></i>
            </div>
            <h4 class="text-muted">Semua Pengembalian Terverifikasi!</h4>
            <p class="text-muted small mb-4">Tidak ada permintaan verifikasi pengembalian saat ini.</p>
        </div>
    </div>
@else
    <!-- Return Request Cards -->
    <div class="row">
        @foreach($loans as $loan)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 loan-card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-info text-dark">
                            Menunggu Konfirmasi
                        </span>
                        <small class="text-muted">
                            <i class="far fa-calendar-check me-1"></i>
                            Tgl. Minta: {{ $loan->updated_at->format('d M Y') }}
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
                    
                    <!-- Return Proof Photo Preview -->
                    <div class="mt-3 p-2 border rounded bg-light">
                        <h6 class="small fw-bold text-muted mb-2"><i class="fas fa-image me-1"></i>Bukti Pengembalian User:</h6>
                        @if($loan->return_photo)
                            <a href="{{ asset('storage/' . $loan->return_photo) }}" target="_blank" title="Klik untuk memperbesar">
                                <img src="{{ asset('storage/' . $loan->return_photo) }}" alt="Bukti Return" class="img-fluid rounded border shadow-sm w-100" style="height:150px; object-fit:cover;">
                            </a>
                            <small class="text-muted d-block mt-1 text-center"><i class="fas fa-search-plus"></i> Klik gambar untuk membesarkan</small>
                        @else
                            <div class="text-center py-4 text-muted fst-italic">
                                <i class="fas fa-image fa-2x mb-2 opacity-50"></i><br>
                                Tidak ada foto bukti yang dilampirkan oleh User.
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-footer bg-white border-0 pb-3">
                    <div class="d-grid gap-2">
                        <form action="{{ route('admin.returns.approve', $loan->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100" style="background:linear-gradient(135deg,#0052D4,#4364F7);border:none;" onclick="return confirm('Apakah Anda yakin gambar bukti sudah valid dan barang dikembalikan dengan selamat?')">
                                <i class="fas fa-undo me-2"></i>Konfirmasi Valid
                            </button>
                        </form>
                        <form action="{{ route('admin.returns.reject', $loan->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Tolak bukti pengembalian? (User harus mengulangi proses pengembalian).')">
                                <i class="fas fa-times me-2"></i>Tolak (Bukti Tidak Valid)
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
