@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <!-- Total Items -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-primary text-white border shadow h-100 summary-card" style="border-radius:15px; background: linear-gradient(135deg, #0052D4, #4364F7, #6FB1FC); border-color: rgba(255,255,255,0.1) !important;">
            <div class="card-body position-relative overflow-hidden">
                <i class="fas fa-boxes position-absolute opacity-25" style="font-size: 5rem; right: -10px; bottom: -10px;"></i>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="text-white-50 fw-bold text-uppercase small tracking-wide">Total Barang</div>
                    <div class="bg-white bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fas fa-cube text-white"></i>
                    </div>
                </div>
                <h2 class="mb-0 display-5 fw-bold">{{ $totalItems }}</h2>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between border-0 bg-transparent py-3">
                <a class="small text-white text-decoration-none stretched-link fw-semibold" href="{{ route('items.index') }}">View Details <i class="fas fa-arrow-right ms-1 transition-transform"></i></a>
            </div>
        </div>
    </div>
    
    <!-- Available Items -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-success text-white border shadow h-100 summary-card" style="border-radius:15px; background: linear-gradient(135deg, #11998e, #38ef7d); border-color: rgba(255,255,255,0.1) !important;">
            <div class="card-body position-relative overflow-hidden">
                <i class="fas fa-check-circle position-absolute opacity-25" style="font-size: 5rem; right: -10px; bottom: -10px;"></i>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="text-white-50 fw-bold text-uppercase small tracking-wide">Tersedia</div>
                    <div class="bg-white bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fas fa-box-open text-white"></i>
                    </div>
                </div>
                <h2 class="mb-0 display-5 fw-bold">{{ $availableItems }}</h2>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between border-0 bg-transparent py-3">
                <a class="small text-white text-decoration-none stretched-link fw-semibold" href="{{ route('items.index') }}?status=tersedia">View Details <i class="fas fa-arrow-right ms-1 transition-transform"></i></a>
            </div>
        </div>
    </div>
    
    <!-- Borrowed Items -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-warning text-white border shadow h-100 summary-card" style="border-radius:15px; background: linear-gradient(135deg, #f2994a, #f2c94c); border-color: rgba(0,0,0,0.05) !important;">
            <div class="card-body position-relative overflow-hidden">
                <i class="fas fa-hand-holding position-absolute opacity-25" style="font-size: 5rem; right: -10px; bottom: -10px;"></i>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="text-white-50 fw-bold text-uppercase small tracking-wide text-dark">Dipinjam</div>
                    <div class="bg-dark bg-opacity-10 rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fas fa-exchange-alt text-dark opacity-75"></i>
                    </div>
                </div>
                <h2 class="mb-0 display-5 fw-bold text-dark">{{ $borrowedItems }}</h2>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between border-0 bg-transparent py-3">
                <a class="small text-dark text-decoration-none stretched-link fw-semibold" href="{{ route('borrowings.index') }}?status=dipinjam">View Details <i class="fas fa-arrow-right ms-1 transition-transform text-dark"></i></a>
            </div>
        </div>
    </div>
    
    <!-- Maintenance Items -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-danger text-white border shadow h-100 summary-card" style="border-radius:15px; background: linear-gradient(135deg, #e60000, #9b2c9b); border-color: rgba(255,255,255,0.1) !important;">
            <div class="card-body position-relative overflow-hidden">
                <i class="fas fa-tools position-absolute opacity-25" style="font-size: 5rem; right: -10px; bottom: -10px;"></i>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="text-white-50 fw-bold text-uppercase small tracking-wide">Maintenance</div>
                    <div class="bg-white bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fas fa-wrench text-white"></i>
                    </div>
                </div>
                <h2 class="mb-0 display-5 fw-bold">{{ $maintenanceItems }}</h2>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between border-0 bg-transparent py-3">
                <a class="small text-white text-decoration-none stretched-link fw-semibold" href="{{ route('items.index') }}?status=maintenance">View Details <i class="fas fa-arrow-right ms-1 transition-transform"></i></a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats Row -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card h-100 border shadow-sm rounded-4 hover-shadow-sm transition-all text-center p-3" style="border-color: rgba(0,0,0,0.08) !important;">
            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle mb-3 d-flex align-items-center justify-content-center" style="width:60px; height:60px;">
                    <i class="fas fa-tags fa-2x"></i>
                </div>
                <div class="display-6 fw-bold text-dark">{{ $totalCategories }}</div>
                <p class="text-muted small fw-semibold text-uppercase tracking-wide mb-3">Kategori</p>
                <a href="{{ route('categories.index') }}" class="btn btn-sm btn-light border rounded-pill px-4 stretched-link text-dark fw-semibold">Lihat <i class="fas fa-arrow-right ms-1 text-muted"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card h-100 border shadow-sm rounded-4 hover-shadow-sm transition-all text-center p-3" style="border-color: rgba(0,0,0,0.08) !important;">
            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                <div class="bg-success bg-opacity-10 text-success rounded-circle mb-3 d-flex align-items-center justify-content-center" style="width:60px; height:60px;">
                    <i class="fas fa-map-marker-alt fa-2x"></i>
                </div>
                <div class="display-6 fw-bold text-dark">{{ $totalLocations }}</div>
                <p class="text-muted small fw-semibold text-uppercase tracking-wide mb-3">Lokasi</p>
                <a href="{{ route('locations.index') }}" class="btn btn-sm btn-light border rounded-pill px-4 stretched-link text-dark fw-semibold">Lihat <i class="fas fa-arrow-right ms-1 text-muted"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card h-100 border shadow-sm rounded-4 hover-shadow-sm transition-all text-center p-3" style="border-color: rgba(0,0,0,0.08) !important;">
            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                <div class="bg-info bg-opacity-10 text-info rounded-circle mb-3 d-flex align-items-center justify-content-center" style="width:60px; height:60px;">
                    <i class="fas fa-users fa-2x"></i>
                </div>
                <div class="display-6 fw-bold text-dark">{{ $totalUsers }}</div>
                <p class="text-muted small fw-semibold text-uppercase tracking-wide mb-3">Total User</p>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-light border rounded-pill px-4 stretched-link text-dark fw-semibold">Lihat <i class="fas fa-arrow-right ms-1 text-muted"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card h-100 border shadow-sm rounded-4 hover-shadow-sm transition-all text-center p-3" style="border-color: rgba(0,0,0,0.08) !important; {{ $overdueBorrowings->count() > 0 ? 'border-bottom: 5px solid #dc3545 !important;' : '' }}">
            <div class="card-body d-flex flex-column justify-content-center align-items-center position-relative">
                @if($overdueBorrowings->count() > 0)
                    <span class="position-absolute top-0 end-0 badge rounded-pill bg-danger shadow-sm pulse-danger"><i class="fas fa-exclamation"></i> Action!</span>
                @endif
                <div class="bg-warning bg-opacity-10 text-warning rounded-circle mb-3 d-flex align-items-center justify-content-center" style="width:60px; height:60px;">
                    <i class="fas fa-clock fa-2x"></i>
                </div>
                <div class="display-6 fw-bold text-dark">{{ $overdueBorrowings->count() }}</div>
                <p class="text-muted small fw-semibold text-uppercase tracking-wide mb-3">Terlambat</p>
                <a href="{{ route('borrowings.index') }}?status=terlambat" class="btn btn-sm btn-light border rounded-pill px-4 stretched-link text-dark fw-semibold" style="position:relative; z-index:5;">Atasi <i class="fas fa-arrow-right ms-1 text-muted"></i></a>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <!-- Borrowing Statistics Chart -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2 text-primary"></i>
                    Statistik Peminjaman (6 Bulan Terakhir)
                </h5>
            </div>
            <div class="card-body">
                <canvas id="borrowingChart" height="100"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Quick Menu -->
    <div class="col-md-4">
        <div class="card h-100 border shadow-sm rounded-4" style="border-color: rgba(0,0,0,0.08) !important;">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-bolt me-2 text-warning"></i> Menu Cepat
                </h6>
            </div>
            <div class="card-body pt-0">
                <div class="d-grid gap-2">
                    <a href="{{ route('items.create') }}" class="btn btn-primary text-start rounded-pill px-4 shadow-sm py-2 hover-lift" style="background:linear-gradient(135deg, #0052D4, #4364F7); border:none;">
                        <i class="fas fa-plus me-2 opacity-75"></i> Barang Baru
                    </a>
                    <a href="{{ route('borrowings.create') }}" class="btn btn-success text-start rounded-pill px-4 shadow-sm py-2 hover-lift" style="background:linear-gradient(135deg, #11998e, #38ef7d); border:none;">
                        <i class="fas fa-hand-holding me-2 opacity-75"></i> Pinjam Manual
                    </a>
                    <a href="{{ route('categories.create') }}" class="btn btn-info text-dark text-start rounded-pill px-4 shadow-sm py-2 hover-lift border-0">
                        <i class="fas fa-tags me-2 opacity-75"></i> Tambah Kategori
                    </a>
                    <a href="{{ route('locations.create') }}" class="btn btn-secondary text-start rounded-pill px-4 shadow-sm py-2 hover-lift border-0">
                        <i class="fas fa-map-marker-alt me-2 opacity-75"></i> Tambah Lokasi
                    </a>
                    <a href="{{ route('admin.laporan.barang') }}" class="btn btn-danger text-start rounded-pill px-4 shadow-sm py-2 hover-lift mt-3 border-0" style="background:linear-gradient(135deg, #e60000, #9b2c9b);">
                        <i class="fas fa-chart-pie me-2 opacity-75"></i> Buka Laporan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Active & Overdue Borrowings -->
<div class="row">
    <!-- Active Borrowings -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-clock me-2 text-warning"></i>
                    Peminjaman Aktif
                </h5>
                <a href="{{ route('borrowings.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @if($activeBorrowings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Peminjam</th>
                                    <th>Barang</th>
                                    <th>Tgl Kembali</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activeBorrowings as $borrowing)
                                    <tr>
                                        <td>
                                            <strong>{{ $borrowing->borrower_name }}</strong>
                                        </td>
                                        <td>{{ $borrowing->item->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($borrowing->expected_return_date)->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge bg-warning">Dipinjam</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">Tidak ada peminjaman aktif</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Overdue Borrowings -->
    <div class="col-md-6">
        <div class="card border-danger">
            <div class="card-header bg-danger text-white border-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Peminjaman Terlambat
                </h5>
                @if($overdueBorrowings->count() > 0)
                    <span class="badge bg-white text-danger">{{ $overdueBorrowings->count() }} Items</span>
                @endif
            </div>
            <div class="card-body p-0">
                @if($overdueBorrowings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3 px-4">Peminjam</th>
                                    <th class="py-3">Barang</th>
                                    <th class="py-3">Tgl Kembali</th>
                                    <th class="py-3 text-end px-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($overdueBorrowings as $borrowing)
                                    <tr class="hover-shadow-row transition-all">
                                        <td class="px-4">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width:32px; height:32px; font-weight:bold;">
                                                    {{ strtoupper(substr($borrowing->borrower_name, 0, 1)) }}
                                                </div>
                                                <strong class="text-dark">{{ $borrowing->borrower_name }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="small fw-semibold">{{ $borrowing->item->name }}</div>
                                            <div class="text-muted" style="font-size:0.75rem;"><i class="fas fa-barcode"></i> {{ $borrowing->item->barcode }}</div>
                                        </td>
                                        <td>
                                            <div class="small text-dark">{{ \Carbon\Carbon::parse($borrowing->expected_return_date)->format('d M Y') }}</div>
                                        </td>
                                        <td class="text-end px-4">
                                            <span class="badge bg-danger rounded-pill px-3 shadow-sm pulse-danger text-white">
                                                Telat {{ \Carbon\Carbon::parse($borrowing->expected_return_date)->diffInDays(now()) }} hari!
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-check-circle fa-4x text-success mb-3 opacity-50"></i>
                        <p class="text-muted fw-semibold mb-0">Semua peminjaman berjalan lancar!</p>
                        <p class="text-muted small">Tidak ada barang yang terlambat pengembaliannya.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($upcomingReturns->count() > 0)
<!-- Upcoming Returns -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card border-warning">
            <div class="card-header bg-warning text-white border-0">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-exclamation me-2"></i>
                    Jatuh Tempo (3 Hari ke Depan)
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Peminjam</th>
                                <th>Barang</th>
                                <th>Tanggal Kembali</th>
                                <th>Sisa Hari</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($upcomingReturns as $borrowing)
                                <tr>
                                    <td><strong>{{ $borrowing->borrower_name }}</strong></td>
                                    <td>{{ $borrowing->item->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($borrowing->expected_return_date)->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ \Carbon\Carbon::parse($borrowing->expected_return_date)->diffInDays(now()) }} hari
                                        </span>
                                    </td>
                                    <td>
                                        <form action="{{ route('borrowings.return', $borrowing) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-undo me-1"></i> Konfirmasi
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Borrowing Statistics Chart
    const ctx = document.getElementById('borrowingChart').getContext('2d');
    const borrowingChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_column($borrowingStats, 'month')) !!},
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: {!! json_encode(array_column($borrowingStats, 'count')) !!},
                backgroundColor: 'rgba(230, 0, 0, 0.7)',
                borderColor: 'rgba(230, 0, 0, 1)',
                borderWidth: 1,
                borderRadius: 6,
                barThickness: 40
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(230, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    padding: 12,
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
<style>
.summary-card {
    transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.3s ease;
}
.summary-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15) !important;
}
.summary-card:hover .fa-arrow-right {
    transform: translateX(5px);
}
.transition-transform {
    transition: transform 0.2s ease;
}
.hover-lift {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.hover-lift:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
}
.pulse-danger {
    animation: pulse-danger 2s infinite;
}
@keyframes pulse-danger {
    0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
    70% { box-shadow: 0 0 0 6px rgba(220, 53, 69, 0); }
    100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
}
.hover-shadow-row:hover {
    background-color: #fff !important;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    transform: translateY(-1px);
    z-index: 10;
    position: relative;
}
.transition-all {
    transition: all 0.2s ease;
}
.tracking-wide {
    letter-spacing: 0.05em;
}
</style>
</script>
@endsection
