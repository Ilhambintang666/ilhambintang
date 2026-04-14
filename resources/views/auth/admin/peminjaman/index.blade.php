@extends('layouts.app')

@section('title', 'Verifikasi Peminjaman')

@section('content')
<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold text-dark">
            <i class="fas fa-clipboard-check me-2 text-primary"></i>
            Verifikasi Peminjaman
        </h3>
        <p class="text-muted mb-0 small">Kelola dan verifikasi permintaan peminjaman barang per transaksi</p>
    </div>
    <div class="text-end">
        <span class="badge bg-warning text-dark fs-6 rounded-pill px-3 py-2 shadow-sm border border-warning">
            <i class="fas fa-clock me-1"></i>
            {{ $groups->count() }} Menunggu
        </span>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-5">
    <div class="col-md-6 mb-3 mb-md-0">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #FF9A9E 0%, #FECFEF 99%, #FECFEF 100%);">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1 text-dark fw-bold display-5">{{ $groups->count() }}</h2>
                        <p class="mb-0 text-dark opacity-75 fw-medium">Transaksi Menunggu Persetujuan</p>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                        <i class="fas fa-hourglass-half fa-2x text-dark opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%);">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1 text-dark fw-bold display-5">{{ \App\Models\LoanGroup::where('status', 'approved')->count() }}</h2>
                        <p class="mb-0 text-dark opacity-75 fw-medium">Total Transaksi Disetujui</p>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                        <i class="fas fa-check-double fa-2x text-dark opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 fade-in">
    <!-- Left Column: Pending Verifications -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
            <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="fas fa-clipboard-list text-primary me-2"></i> Menunggu Verifikasi
                    @if($groups->count() > 0)
                        <span class="badge bg-danger rounded-pill ms-2 align-top" style="font-size: 0.75rem;">{{ $groups->count() }} Transaksi</span>
                    @endif
                </h5>
            </div>
            <div class="card-body p-4 bg-light bg-opacity-50">
                @if($groups->isEmpty())
                    <!-- Empty State -->
                    <div class="text-center py-5 my-3 bg-white rounded-4 shadow-sm border border-light">
                        <div class="mb-4 d-inline-flex align-items-center justify-content-center bg-light rounded-circle" style="width: 80px; height: 80px;">
                            <i class="fas fa-clipboard-check fa-2x text-success"></i>
                        </div>
                        <h5 class="fw-bold text-dark">Luar Biasa! Semua Beres</h5>
                        <p class="text-muted mb-0 small">Tidak ada permintaan peminjaman baru yang menunggu verifikasi saat ini.</p>
                    </div>
                @else
                    <div class="d-flex flex-column gap-4">
                        @foreach($groups as $group)
                            <!-- Group Card -->
                            <div class="card border border-light shadow-sm rounded-4 overflow-hidden transaction-card bg-white">
                                <!-- Card Header -->
                                <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-start">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white shadow-sm flex-shrink-0" 
                                             style="width: 45px; height: 45px; background: linear-gradient(135deg, #0ba360 0%, #3cba92 100%); font-size: 1.1rem;">
                                            {{ strtoupper(substr($group->user->name ?? 'A', 0, 1)) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-bold text-dark">{{ $group->user->name ?? 'N/A' }}</h6>
                                            <div class="text-muted small d-flex align-items-center gap-1" style="font-size: 0.75rem;">
                                                <i class="fas fa-envelope"></i> {{ $group->user->email ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-muted bg-light px-2 py-1 rounded-pill" style="font-size: 0.7rem;">
                                        <i class="fas fa-calendar-plus me-1"></i> Diajukan: {{ $group->created_at->format('d M Y') }}
                                    </div>
                                </div>

                                <!-- Card Body -->
                                <div class="card-body p-3">
                                    <div class="row g-2 mb-3">
                                        <div class="col-sm-6">
                                            <div class="bg-light p-2 rounded-3 border border-light d-flex flex-column h-100 text-center">
                                                <span class="text-muted fw-medium mb-1 text-uppercase tracking-wide" style="font-size: 0.65rem;">Tgl. Pengambilan</span>
                                                <strong class="text-dark small"><i class="fas fa-sign-out-alt me-1 text-primary"></i>{{ \Carbon\Carbon::parse($group->borrow_date)->format('d M Y') }}</strong>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="bg-light p-2 rounded-3 border border-light d-flex flex-column h-100 text-center">
                                                <span class="text-muted fw-medium mb-1 text-uppercase tracking-wide" style="font-size: 0.65rem;">Tenggat Pengembalian</span>
                                                <strong class="text-danger small"><i class="fas fa-sign-in-alt me-1 text-danger"></i>{{ \Carbon\Carbon::parse($group->expected_return_date)->format('d M Y') }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Items Summary (Accordion) -->
                                    @php
                                        $itemCounts = $group->loans->groupBy(function($l) { return $l->item->name; })->map->count();
                                    @endphp
                                    
                                    <div class="accordion accordion-flush bg-white rounded-3 border border-light shadow-sm overflow-hidden" id="accordionGroup{{ $group->id }}">
                                        <div class="accordion-item border-0">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed py-2 fw-bold text-dark small" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGroup{{ $group->id }}" aria-expanded="false" aria-controls="collapseGroup{{ $group->id }}">
                                                    <div class="d-flex align-items-center gap-2 w-100 pe-3">
                                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 0.8rem;">
                                                            <i class="fas fa-box"></i>
                                                        </div>
                                                        <span>Minta Pinjam <span class="text-primary">{{ $group->loans->count() }} Unit</span> Barang</span>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapseGroup{{ $group->id }}" class="accordion-collapse collapse" data-bs-parent="#accordionGroup{{ $group->id }}">
                                                <div class="accordion-body pt-0 pb-3 px-3">
                                                    
                                                    <!-- Summary by item name -->
                                                    <div class="d-flex flex-wrap gap-1 mb-2 mt-1">
                                                        @foreach($itemCounts as $name => $count)
                                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1 rounded-pill" style="font-size: 0.7rem;">
                                                                {{ $count }}× {{ $name }}
                                                            </span>
                                                        @endforeach
                                                    </div>

                                                    <hr class="border-light opacity-50 my-2">
                                                    
                                                    <!-- Detailed Item List (Barcodes) -->
                                                    <div class="fw-bold text-muted mb-1 text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">Detail Unit (Barcode):</div>
                                                    <ul class="list-group list-group-flush">
                                                        @foreach($group->loans as $loan)
                                                            <li class="list-group-item px-0 py-1 border-dashed bg-transparent d-flex justify-content-between align-items-center">
                                                                <span class="text-dark fw-medium" style="font-size: 0.8rem;">{{ $loan->item->name }}</span>
                                                                @if($loan->item->barcode)
                                                                    <span class="font-monospace text-primary bg-primary bg-opacity-10 px-2 py-1 rounded fw-bold" style="font-size: 0.7rem;">
                                                                        <i class="fas fa-barcode opacity-75 me-1"></i>{{ $loan->item->barcode }}
                                                                    </span>
                                                                @else
                                                                    <span class="text-muted fst-italic" style="font-size: 0.7rem;">Tanpa barcode</span>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card Footer Actions -->
                                <div class="card-footer bg-white border-top p-3">
                                    <div class="row g-2">
                                        <div class="col-sm-6">
                                            <form action="{{ route('admin.peminjaman.group.approve', $group->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-success w-100 py-2 fw-bold shadow-sm btn-sm" onclick="return confirm('Setujui transaksi ini beserta semua unit barangnya?')">
                                                    <i class="fas fa-check-circle me-1"></i>Setuju
                                                </button>
                                            </form>
                                        </div>
                                        <div class="col-sm-6">
                                            <form action="{{ route('admin.peminjaman.group.reject', $group->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger w-100 py-2 fw-bold btn-sm" onclick="return confirm('Tolak transaksi ini?')">
                                                    <i class="fas fa-times-circle me-1"></i>Tolak
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Group Card -->
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column: Item Stock Overview -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
            <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="fas fa-boxes text-warning me-2"></i> Status Stok Barang 
                </h5>
            </div>
            <div class="card-body p-0">
                @if($stockItems->count() > 0)
                    <div class="table-responsive" style="max-height: 800px; overflow-y: auto;">
                        <table class="table table-hover align-middle mb-0 border-0">
                            <tbody>
                                @foreach($stockItems as $item)
                                    <tr class="border-bottom">
                                        <td class="px-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3 text-primary" style="width: 45px; height: 45px;">
                                                    <i class="fas fa-cube fs-5"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">{{ $item->name }}</div>
                                                    <div class="d-flex align-items-center gap-1">
                                                        <span class="badge bg-light border text-dark" style="font-size: 0.7rem;">{{ $item->category->name ?? '-' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-end" style="min-width: 120px;">
                                            <div class="d-flex flex-column align-items-end gap-1">
                                                <span class="badge {{ $item->available_stock > 0 ? 'bg-success' : 'bg-danger' }} bg-opacity-10 text-{{ $item->available_stock > 0 ? 'success' : 'danger' }} border border-{{ $item->available_stock > 0 ? 'success' : 'danger' }} border-opacity-25 px-2 py-1 rounded-pill fw-medium" style="font-size: 0.7rem;">
                                                    <i class="fas {{ $item->available_stock > 0 ? 'fa-check' : 'fa-times' }} me-1"></i>{{ $item->available_stock }} Tersedia
                                                </span>
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1 rounded-pill fw-medium" style="font-size: 0.7rem;">
                                                    <i class="fas fa-hand-holding me-1"></i>{{ $item->borrowed_stock }} Dipinjam
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <!-- Empty State for Items -->
                    <div class="text-center py-5 my-3">
                        <div class="fw-bold text-dark">Data Stok Kosong</div>
                        <p class="text-muted small mb-0">Belum ada barang yang terdaftar untuk dipinjam.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .tracking-wide { letter-spacing: 0.5px; }
    .border-dashed { border-bottom: 1px dashed rgba(0,0,0,0.1) !important; }
    .list-group-item:last-child { border-bottom: none !important; }
    
    .transaction-card {
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    
    .transaction-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.05) !important;
        border-color: rgba(0,0,0,0.1) !important;
    }
    
    .accordion-button:not(.collapsed) {
        background-color: transparent !important;
        color: var(--bs-dark);
        box-shadow: none;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .accordion-button:focus {
        border-color: transparent;
        box-shadow: none;
    }
</style>
@endsection
