@extends('layouts.app')

@section('title', 'Detail Lokasi')

@section('content')
{{-- Page Header --}}
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h3><i class="fas fa-map-marker-alt me-2 text-danger"></i>Detail Lokasi</h3>
        <p class="text-muted mb-0 small">Informasi lengkap lokasi penyimpanan</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('locations.edit', $location) }}" class="btn btn-warning rounded-pill px-4 shadow-sm fw-semibold hover-lift text-dark">
            <i class="fas fa-edit me-1"></i> Edit
        </a>
        <a href="{{ route('locations.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm fw-semibold hover-lift">
            <i class="fas fa-arrow-left me-1 text-muted"></i> Kembali
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-header border-0 py-4" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
                <h5 class="mb-0 fw-bold text-dark">
                    <div style="width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg,#e60000,#9b2c9b);display:inline-flex;align-items:center;justify-content:center;color:#fff;margin-right:10px;box-shadow:0 4px 6px rgba(230,0,0,0.2);">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    Informasi Lokasi
                </h5>
            </div>
            <div class="card-body p-4 p-md-5">
                <div class="d-flex align-items-center mb-5 p-4 rounded-4" style="background:linear-gradient(135deg, rgba(230,0,0,0.03), rgba(155,44,155,0.03)); border: 1px solid rgba(230,0,0,0.05);">
                    <div style="width:70px;height:70px;border-radius:16px;background:linear-gradient(135deg,#e60000,#9b2c9b);display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow: 0 8px 16px rgba(230,0,0,0.2);" class="me-4 shadow-lg">
                        <i class="fas fa-map-marker-alt fa-2x text-white"></i>
                    </div>
                    <div>
                        <h3 class="mb-2 fw-bold text-dark">{{ $location->name }}</h3>
                        <span class="badge rounded-pill bg-danger shadow-sm px-3 py-2 fw-semibold">
                            <i class="fas fa-box me-1"></i> {{ $location->items->count() }} barang tersimpan
                        </span>
                    </div>
                </div>

                <div class="table-responsive mb-4">
                    <table class="table table-borderless align-middle">
                        <tbody>
                            <tr class="border-bottom border-light">
                                <td width="35%" class="text-muted fw-semibold py-3 text-uppercase small tracking-wide"><i class="fas fa-tag me-2 text-danger"></i>Nama Lokasi</td>
                                <td class="py-3"><span class="fw-bold fs-6 text-dark">{{ $location->name }}</span></td>
                            </tr>
                            <tr class="border-bottom border-light">
                                <td class="text-muted fw-semibold py-3 text-uppercase small tracking-wide"><i class="fas fa-align-left me-2 text-danger"></i>Deskripsi</td>
                                <td class="py-3">{{ $location->description ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold py-3 text-uppercase small tracking-wide"><i class="fas fa-cubes me-2 text-danger"></i>Total Barang</td>
                                <td class="py-3"><span class="badge bg-danger rounded-pill shadow-sm px-3">{{ $location->items->count() }} barang</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 mb-5">
                    <h6 class="fw-bold mb-3 d-flex align-items-center text-dark">
                        <i class="fas fa-box-open text-danger me-2"></i> Daftar Barang di Lokasi Ini
                    </h6>
                    @if($location->items->count() > 0)
                        <div class="table-responsive rounded-3 border">
                            <table class="table table-hover align-middle mb-0">
                                <thead style="background-color: #f8f9fa;">
                                    <tr>
                                        <th class="text-uppercase text-muted" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; padding: 12px 16px;">Info Barang</th>
                                        <th class="text-uppercase text-muted" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; padding: 12px 16px;">Status</th>
                                        <th class="text-uppercase text-muted" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; padding: 12px 16px;">Kondisi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($location->items as $item)
                                    <tr>
                                        <td class="px-3 py-3">
                                            <div class="fw-bold text-dark mb-1">{{ $item->name }}</div>
                                            <code class="bg-light text-secondary px-2 py-0 rounded border small me-2" style="font-size: 0.7rem;">{{ $item->barcode }}</code>
                                            @if($item->is_loanable)
                                                <span class="badge rounded-pill" style="background: rgba(25, 135, 84, 0.1); color: #198754; font-size: 0.65rem;">Pinjaman</span>
                                            @else
                                                <span class="badge rounded-pill" style="background: rgba(108, 117, 125, 0.1); color: #6c757d; font-size: 0.65rem;">Aset Tetap</span>
                                            @endif
                                        </td>
                                        <td class="px-3">
                                            @switch($item->status)
                                                @case('tersedia')
                                                    <span class="badge rounded-pill" style="background: linear-gradient(135deg, #28a745, #20c997);"><i class="fas fa-check-circle me-1"></i>Tersedia</span>
                                                    @break
                                                @case('dipinjam')
                                                    <span class="badge rounded-pill" style="background: linear-gradient(135deg, #ffc107, #fd7e14);"><i class="fas fa-handshake me-1"></i>Dipinjam</span>
                                                    @break
                                                @case('maintenance')
                                                    <span class="badge rounded-pill" style="background: linear-gradient(135deg, #dc3545, #e83e8c);"><i class="fas fa-tools me-1"></i>Maintenance</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td class="px-3">
                                            @switch($item->condition)
                                                @case('baik')
                                                    <span class="small fw-bold" style="color: #28a745; font-size: 0.75rem;"><i class="fas fa-circle me-1" style="font-size: 6px; vertical-align: middle;"></i>Kondisi Baik</span>
                                                    @break
                                                @case('rusak')
                                                    <span class="small fw-bold" style="color: #dc3545; font-size: 0.75rem;"><i class="fas fa-circle me-1" style="font-size: 6px; vertical-align: middle;"></i>Kondisi Rusak</span>
                                                    @break
                                                @case('dalam_perbaikan')
                                                    <span class="small fw-bold" style="color: #ffc107; font-size: 0.75rem;"><i class="fas fa-circle me-1" style="font-size: 6px; vertical-align: middle;"></i>Dalam Perbaikan</span>
                                                    @break
                                            @endswitch
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center p-4 border rounded-3 bg-light">
                            <i class="fas fa-inbox fa-3x text-muted mb-3 opacity-50"></i>
                            <h6 class="fw-bold text-muted">Belum Ada Barang</h6>
                            <p class="text-muted small mb-0">Lokasi ini belum memiliki barang yang ditugaskan.</p>
                        </div>
                    @endif
                </div>

                <div class="d-flex justify-content-between align-items-center pt-4 border-top">
                    <a href="{{ route('locations.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm fw-semibold hover-lift">
                        <i class="fas fa-arrow-left me-1 text-muted"></i> Kembali
                    </a>
                    <a href="{{ route('locations.edit', $location) }}" class="btn btn-danger rounded-pill px-5 shadow-sm fw-semibold hover-lift" style="background:linear-gradient(135deg, #e60000, #9b2c9b); border:none;">
                        <i class="fas fa-edit me-1"></i> Edit Lokasi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
.tracking-wide {
    letter-spacing: 0.05em;
}
.hover-lift {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.hover-lift:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.1) !important;
}
</style>
@endsection
