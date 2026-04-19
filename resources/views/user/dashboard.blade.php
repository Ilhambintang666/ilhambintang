@extends('layouts.app')

@section('title', 'Dashboard User')

@section('content')
<style>
    .welcome-banner {
        background: linear-gradient(135deg, rgba(230,0,0,0.9), rgba(155,44,155,0.9)), url('https://www.transparenttextures.com/patterns/cubes.png');
        border-radius: 16px;
        color: white;
        padding: 3rem 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(230,0,0,0.2);
    }
    .welcome-banner::after {
        content: '';
        position: absolute;
        bottom: -40px;
        right: -20px;
        opacity: 0.15;
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        content: "\f4c4"; /* hand-holding-box */
        font-size: 14rem;
        transform: rotate(-15deg);
    }
    /* Stats Cards Animation */
    .stat-card-modern {
        border-radius: 16px;
        padding: 24px;
        color: white;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.1);
        height: 100%;
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    }
    .stat-card-modern:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }
    .stat-card-modern .stat-icon {
        font-size: 4.5rem;
        opacity: 0.2;
        position: absolute;
        right: -10px;
        bottom: -15px;
        transition: transform 0.4s;
    }
    .stat-card-modern:hover .stat-icon {
        transform: scale(1.1) rotate(-10deg);
        opacity: 0.3;
    }
    .stat-value {
        font-size: 3rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 0.5rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
    }
    .stat-label {
        font-size: 1.15rem;
        font-weight: 600;
        opacity: 0.95;
    }
    /* Item Card */
    .item-card {
        border-radius: 14px;
        border: 1px solid rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        background: white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    .item-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.06);
        border-color: rgba(230,0,0,0.15);
    }
    .quick-action-btn {
        transition: all 0.3s ease;
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    .quick-action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(230,0,0,0.25);
    }
    .modern-card {
        border-radius: 18px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border: 1px solid rgba(0,0,0,0.08);
    }
    .modern-card-header {
        background: white;
        border-radius: 18px 18px 0 0 !important;
        border-bottom: 1px solid rgba(0,0,0,0.04);
        padding: 1.5rem;
    }
    .empty-state {
        padding: 3.5rem 1.5rem;
        text-align: center;
    }
    .empty-state-icon {
        font-size: 4.5rem;
        color: #e9ecef;
        margin-bottom: 1.5rem;
    }
    
    .return-form-bg {
        background: rgba(248, 249, 250, 0.8);
        backdrop-filter: blur(4px);
    }
    
    .table-hover tbody tr {
        transition: all 0.2s ease;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(230, 0, 0, 0.02) !important;
        transform: scale(1.01);
    }
</style>

<!-- Welcome Section -->
<div class="row mb-4 fade-in">
    <div class="col-md-12">
        <div class="welcome-banner">
            <div class="row align-items-center position-relative" style="z-index: 2;">
                <div class="col-md-9">
                    <h2 class="fw-bold mb-2">Selamat Datang, {{ auth()->user()->name }}! 👋</h2>
                    <p class="fs-5 opacity-75 mb-4">Platform Peminjaman Inventaris PMI Kota Semarang</p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="{{ route('user.borrow') }}" class="btn btn-light text-danger quick-action-btn border-0 shadow-sm">
                            <i class="fas fa-plus-circle me-2"></i> Pinjam Barang Baru
                        </a>
                        <a href="{{ route('user.my-borrowings') }}" class="btn btn-outline-light quick-action-btn">
                            <i class="fas fa-history me-2"></i> Riwayat Saya
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4 g-4 fade-in" style="animation-delay: 0.1s;">
    <div class="col-md-4">
        <div class="stat-card-modern" style="background: linear-gradient(135deg, #FF416C 0%, #FF4B2B 100%);">
            <div class="stat-value">{{ $activeLoans->count() }}</div>
            <div class="stat-label">Peminjaman Aktif</div>
            <div class="mt-3 small opacity-75">
                <i class="fas fa-clock me-1"></i> Barang yang sedang Anda pinjam
            </div>
            <div class="stat-icon">
                <i class="fas fa-hand-holding-box" style="font-family: 'Font Awesome 6 Free';"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card-modern" style="background: linear-gradient(135deg, #F5AF19 0%, #F12711 100%);">
            <div class="stat-value">{{ $pendingReturns->count() }}</div>
            <div class="stat-label">Menunggu Verifikasi</div>
            <div class="mt-3 small opacity-75">
                <i class="fas fa-hourglass-half me-1"></i> Pengembalian menunggu admin
            </div>
            <div class="stat-icon">
                <i class="fas fa-clipboard-check"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card-modern" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
            <div class="stat-value">{{ $returnedLoans->count() }}</div>
            <div class="stat-label">Selesai Dikembalikan</div>
            <div class="mt-3 small opacity-75">
                <i class="fas fa-check-double me-1"></i> Total riwayat selesai
            </div>
            <div class="stat-icon">
                <i class="fas fa-box-open"></i>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="row g-4 mb-4 fade-in" style="animation-delay: 0.2s;">
    <!-- Active Loans Section -->
    <div class="col-lg-7">
        <div class="card modern-card h-100">
            <div class="card-header modern-card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="fas fa-bolt text-warning me-2"></i> Peminjaman Aktif 
                    @if($activeLoans->count() > 0)
                        <span class="badge bg-danger rounded-pill ms-2 ms-1 align-top" style="font-size: 0.75rem;">{{ $activeLoans->count() }} Item</span>
                    @endif
                </h5>
                <a href="{{ route('user.my-borrowings') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    Detail <i class="fas fa-chevron-right ms-1" style="font-size: 0.8rem;"></i>
                </a>
            </div>
            <div class="card-body p-4">
                @if($activeLoans->count() > 0)
                    <div class="d-flex flex-column gap-3">
                        @foreach($activeLoans as $loan)
                            <div class="item-card p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="fw-bold mb-2 text-dark">{{ $loan->item->name }}</h5>
                                        <div class="d-flex flex-wrap gap-3 text-muted small mt-1">
                                            <span class="d-flex align-items-center bg-light px-2 py-1 rounded">
                                                <i class="far fa-calendar-alt text-primary me-2"></i> 
                                                <span>Pinjam: <span class="fw-medium text-dark">{{ \Carbon\Carbon::parse($loan->created_at)->format('d M Y') }}</span></span>
                                            </span>
                                            <span class="d-flex align-items-center bg-light px-2 py-1 rounded">
                                                <i class="far fa-calendar-check text-danger me-2"></i> 
                                                <span>Tenggat: <span class="fw-medium text-danger">{{ \Carbon\Carbon::parse($loan->expected_return_date)->format('d M Y') }}</span></span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column align-items-end gap-2">
                                        <div class="bg-success bg-opacity-10 text-success px-3 py-1 rounded-pill fw-semibold text-nowrap border border-success border-opacity-25 small">
                                            <i class="fas fa-check-circle me-1"></i> Disetujui
                                        </div>
                                        <button class="btn btn-primary btn-sm px-3 rounded-pill border-0 shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#returnForm-{{ $loan->id }}" aria-expanded="false" aria-controls="returnForm-{{ $loan->id }}">
                                            <i class="fas fa-undo me-1"></i> Kembalikan
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="collapse" id="returnForm-{{ $loan->id }}">
                                    <div class="return-form-bg p-3 rounded-3 mt-3 border">
                                        <h6 class="small fw-bold text-dark mb-2"><i class="fas fa-camera me-1 text-primary"></i> Upload Foto Terkini</h6>
                                        <form method="POST" action="{{ route('user.return', $loan) }}" enctype="multipart/form-data" class="row g-3 align-items-end">
                                            @csrf
                                            <div class="col-md-5 col-sm-12">
                                                <label class="form-label small fw-medium mb-1 text-secondary">Foto Barang Saat Ini</label>
                                                <input type="file" name="return_photo" class="form-control form-control-sm border-0 shadow-sm" required>
                                            </div>
                                            <div class="col-md-4 col-sm-12">
                                                <label class="form-label small fw-medium mb-1 text-secondary">Tanggal Kembali</label>
                                                <input type="date" name="return_date" class="form-control form-control-sm border-0 shadow-sm" required>
                                            </div>
                                            <div class="col-md-3 col-sm-12 mt-3 mt-md-0">
                                                <button type="submit" class="btn btn-success w-100 py-2 fw-medium shadow-sm rounded-3">
                                                    Konfirmasi
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-box-open empty-state-icon"></i>
                        <h5 class="text-secondary fw-bold">Belum Ada Peminjaman</h5>
                        <p class="text-muted">Anda belum memiliki barang yang sedang dipinjam saat ini.</p>
                        <a href="{{ route('user.borrow') }}" class="btn btn-primary rounded-pill px-4 py-2 mt-3 shadow-sm font-weight-bold">
                            <i class="fas fa-search me-2"></i>Cari Barang Sekarang
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Available Items Section -->
    <div class="col-lg-5">
        <div class="card modern-card h-100">
            <div class="card-header modern-card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="fas fa-star text-warning me-2"></i> Rekomendasi 
                </h5>
                <a href="{{ route('user.borrow') }}" class="btn btn-sm btn-light text-primary fw-medium rounded-pill px-3">
                    Semua <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                @if($availableItems->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 border-0">
                            <tbody>
                                @foreach($availableItems->take(5) as $item)
                                    <tr class="border-bottom">
                                        <td class="px-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3 text-primary" style="width: 45px; height: 45px;">
                                                    <i class="fas fa-cube fs-5"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark mb-1">{{ $item->name }}</div>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-light border text-dark">{{ $item->category->name ?? '-' }}</span>
                                                        <small class="text-muted"><i class="fas fa-map-marker-alt me-1 text-danger"></i>{{ $item->location->name ?? 'Gudang' }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-end">
                                            <button type="button" class="btn btn-primary btn-sm rounded-pill shadow-sm fw-medium px-3" data-bs-toggle="modal" data-bs-target="#borrowModal-{{ $item->id }}">
                                                <i class="fas fa-hand-holding me-1"></i> Pinjam
                                            </button>
                                            
                                            <!-- Modal Pinjam -->
                                            <div class="modal fade" id="borrowModal-{{ $item->id }}" tabindex="-1" aria-labelledby="borrowModalLabel-{{ $item->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered text-start">
                                                    <div class="modal-content border-0 shadow">
                                                        <div class="modal-header border-bottom-0 pb-0">
                                                            <h5 class="modal-title fw-bold" id="borrowModalLabel-{{ $item->id }}">Pinjam Barang</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-3">
                                                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3 text-primary" style="width: 45px; height: 45px;">
                                                                    <i class="fas fa-cube fs-5"></i>
                                                                </div>
                                                                <div>
                                                                    <div class="fw-bold text-dark fs-5">{{ $item->name }}</div>
                                                                    <div class="d-flex align-items-center gap-2 mt-1">
                                                                        <span class="badge bg-white border text-dark">{{ $item->category->name ?? '-' }}</span>
                                                                        <small class="text-muted"><i class="fas fa-map-marker-alt me-1 text-danger"></i>{{ $item->location->name ?? 'Gudang' }}</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <form method="POST" action="{{ route('user.borrow.submit') }}">
                                                                @csrf
                                                                <input type="hidden" name="item_id" value="{{ $item->id }}">
                                                                <div class="mb-3">
                                                                    <label class="form-label small fw-medium text-secondary">Tanggal Pinjam</label>
                                                                    <input type="date" name="borrow_date" class="form-control" required>
                                                                </div>
                                                                <div class="mb-4">
                                                                    <label class="form-label small fw-medium text-secondary">Rencana Tanggal Kembali</label>
                                                                    <input type="date" name="expected_return_date" class="form-control" required>
                                                                </div>
                                                                <button type="submit" class="btn btn-primary w-100 py-2 fw-medium shadow-sm rounded-3 fs-6">
                                                                    Konfirmasi Peminjaman
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-search empty-state-icon"></i>
                        <h5 class="text-secondary fw-bold">Barang Kosong</h5>
                        <p class="text-muted">Saat ini tidak ada barang yang tersedia untuk dipinjam.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
