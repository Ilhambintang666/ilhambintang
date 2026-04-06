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
