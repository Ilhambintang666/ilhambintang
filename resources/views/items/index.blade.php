@extends('layouts.app')

@section('title', 'Kelola Barang')

@section('content')
{{-- Page Header --}}
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h3><i class="fas fa-box me-2 text-danger"></i>Kelola Barang</h3>
        <p class="text-muted mb-0 small">Kelola seluruh barang inventaris PMI Semarang</p>
    </div>
    <a href="{{ route('items.create') }}" class="btn btn-danger">
        <i class="fas fa-plus me-2"></i>Tambah Barang
    </a>
</div>

{{-- Filter Section --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 py-3">
        <h6 class="mb-0">
            <i class="fas fa-filter me-2 text-danger"></i>Filter & Pencarian
        </h6>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Kondisi</label>
                <select name="condition" class="form-select">
                    <option value="">Semua Kondisi</option>
                    <option value="baik" {{ request('condition') == 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak" {{ request('condition') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                    <option value="dalam_perbaikan" {{ request('condition') == 'dalam_perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Pencarian</label>
                <input type="text" name="search" class="form-control" 
                       placeholder="Cari nama/barcode..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-danger w-100">
                    <i class="fas fa-search me-1"></i>Filter
                </button>
                <a href="{{ route('items.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Items Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0">
            <i class="fas fa-list me-2 text-danger"></i>
            Daftar Barang Inventaris
        </h5>
        <span class="badge bg-danger rounded-pill">{{ $items->count() }} Barang</span>
    </div>
    <div class="card-body p-0">
        @if($items->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr style="border-bottom: 2px solid #e60000;">
                            <th style="font-size:0.82rem; text-transform:uppercase; letter-spacing:.5px; padding:16px 16px; color:#333; font-weight:600;">No</th>
                            <th style="font-size:0.82rem; text-transform:uppercase; letter-spacing:.5px; padding:16px 12px; color:#333; font-weight:600;">Nama Barang</th>
                            <th style="font-size:0.82rem; text-transform:uppercase; letter-spacing:.5px; padding:16px 12px; color:#333; font-weight:600;">Barcode</th>
                            <th style="font-size:0.82rem; text-transform:uppercase; letter-spacing:.5px; padding:16px 12px; color:#333; font-weight:600;">Kategori</th>
                            <th style="font-size:0.82rem; text-transform:uppercase; letter-spacing:.5px; padding:16px 12px; color:#333; font-weight:600;">Lokasi</th>
                            <th style="font-size:0.82rem; text-transform:uppercase; letter-spacing:.5px; padding:16px 12px; color:#333; font-weight:600;">Qty</th>
                            <th style="font-size:0.82rem; text-transform:uppercase; letter-spacing:.5px; padding:16px 12px; color:#333; font-weight:600;">Status</th>
                            <th style="font-size:0.82rem; text-transform:uppercase; letter-spacing:.5px; padding:16px 12px; color:#333; font-weight:600;">Kondisi</th>
                            <th style="font-size:0.82rem; text-transform:uppercase; letter-spacing:.5px; padding:16px 12px; color:#333; font-weight:600;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @foreach($items as $index => $item)
                        <tr style="transition: all 0.3s;" class="hover-shadow-row">
                            <td class="px-4"><strong class="text-muted">{{ $index + 1 }}</strong></td>
                            <td>
                                <strong>{{ $item->name }}</strong>
                                @if($item->description)
                                    <br><small class="text-muted">{{ Str::limit($item->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <code class="bg-light px-2 py-1 rounded">{{ $item->barcode }}</code>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border shadow-sm rounded-pill px-3"><i class="fas fa-folder me-1 text-danger"></i>{{ $item->category->name }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border shadow-sm rounded-pill px-3"><i class="fas fa-map-marker-alt me-1 text-success"></i>{{ $item->location->name }}</span>
                            </td>
                            <td>
                                <strong>{{ $item->quantity }}</strong>
                            </td>
                            <td>
                                @switch($item->status)
                                    @case('tersedia')
                                        <span class="badge bg-success rounded-pill px-3 shadow-sm"><i class="fas fa-check me-1"></i>Tersedia</span>
                                        @break
                                    @case('dipinjam')
                                        <span class="badge bg-warning rounded-pill px-3 shadow-sm text-dark"><i class="fas fa-hand-holding me-1"></i>Dipinjam</span>
                                        @break
                                    @case('maintenance')
                                        <span class="badge bg-danger rounded-pill px-3 shadow-sm"><i class="fas fa-tools me-1"></i>Maintenance</span>
                                        @break
                                @endswitch
                            </td>
                            <td>
                                @switch($item->condition)
                                    @case('baik')
                                        <span class="badge bg-success rounded-pill px-3 shadow-sm"><i class="fas fa-check-circle me-1"></i>Baik</span>
                                        @break
                                    @case('rusak')
                                        <span class="badge bg-danger rounded-pill px-3 shadow-sm"><i class="fas fa-times-circle me-1"></i>Rusak</span>
                                        @break
                                    @case('dalam_perbaikan')
                                        <span class="badge bg-warning rounded-pill px-3 shadow-sm text-dark"><i class="fas fa-tools me-1"></i>Perbaikan</span>
                                        @break
                                @endswitch
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('items.show', $item) }}"
                                       class="btn btn-sm btn-light text-info border shadow-sm rounded-pill" title="Lihat Detail" style="width:32px; height:32px; display:flex; align-items:center; justify-content:center; transition:all 0.2s;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('items.edit', $item) }}"
                                       class="btn btn-sm btn-light text-warning border shadow-sm rounded-pill" title="Edit" style="width:32px; height:32px; display:flex; align-items:center; justify-content:center; transition:all 0.2s;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('items.qrcode', $item->id) }}"
                                       class="btn btn-sm btn-light text-secondary border shadow-sm rounded-pill" title="QR Code" style="width:32px; height:32px; display:flex; align-items:center; justify-content:center; transition:all 0.2s;">
                                        <i class="fas fa-qrcode"></i>
                                    </a>
                                    <form action="{{ route('items.destroy', $item) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus barang {{ $item->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger border shadow-sm rounded-pill" title="Hapus" style="width:32px; height:32px; display:flex; align-items:center; justify-content:center; transition:all 0.2s;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-3">
                    <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#e60000,#9b2c9b);display:flex;align-items:center;justify-content:center;margin:0 auto;box-shadow:0 10px 20px rgba(230,0,0,0.2);">
                        <i class="fas fa-box fa-2x text-white"></i>
                    </div>
                </div>
                <h5 class="text-muted fw-bold">Belum ada barang</h5>
                <p class="text-muted small mb-4">Mulai lengkapi data inventaris PMI Semarang.</p>
                <a href="{{ route('items.create') }}" class="btn btn-danger rounded-pill px-4 shadow-sm" style="background:linear-gradient(135deg, #e60000, #9b2c9b); border:none;">
                    <i class="fas fa-plus me-2"></i>Tambah Barang Pertama
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<style>
.hover-shadow-row:hover {
    background-color: #fff !important;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    transform: translateY(-2px);
    z-index: 10;
    position: relative;
}
.btn-light:hover {
    background-color: #f8f9fa;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important;
}
</style>
@endsection