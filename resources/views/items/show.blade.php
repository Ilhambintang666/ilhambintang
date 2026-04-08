@extends('layouts.app')

@section('title', 'Detail Barang')

@section('content')
{{-- Page Header --}}
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h3><i class="fas fa-box me-2 text-danger"></i>Detail Barang</h3>
        <p class="text-muted mb-0 small">Informasi lengkap barang inventaris</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('items.edit', $item) }}" class="btn btn-warning rounded-pill px-4 shadow-sm fw-semibold hover-lift text-dark">
            <i class="fas fa-edit me-1"></i> Edit
        </a>
        <a href="{{ route('items.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm fw-semibold hover-lift">
            <i class="fas fa-arrow-left me-1 text-muted"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        {{-- Info Utama --}}
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4">
            <div class="card-header border-0 py-4" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
                <h5 class="mb-0 fw-bold text-dark">
                    <div style="width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg,#e60000,#9b2c9b);display:inline-flex;align-items:center;justify-content:center;color:#fff;margin-right:10px;box-shadow:0 4px 6px rgba(230,0,0,0.2);">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    Informasi Barang
                </h5>
            </div>
            <div class="card-body p-4 p-md-5">
                <div class="d-flex align-items-center mb-5 p-4 rounded-4" style="background:linear-gradient(135deg, rgba(230,0,0,0.03), rgba(155,44,155,0.03)); border: 1px solid rgba(230,0,0,0.05);">
                    <div style="width:70px;height:70px;border-radius:16px;background:linear-gradient(135deg,#e60000,#9b2c9b);display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow: 0 8px 16px rgba(230,0,0,0.2);" class="me-4 shadow-lg">
                        <i class="fas fa-box fa-2x text-white"></i>
                    </div>
                    <div>
                        <h3 class="mb-2 fw-bold text-dark">{{ $item->name }}</h3>
                        <div class="d-flex gap-2 flex-wrap">
                            @switch($item->status)
                                @case('tersedia')
                                    <span class="badge rounded-pill bg-success shadow-sm px-3 py-2"><i class="fas fa-check-circle me-1"></i> Tersedia</span>
                                    @break
                                @case('dipinjam')
                                    <span class="badge rounded-pill bg-warning text-dark shadow-sm px-3 py-2"><i class="fas fa-hand-holding me-1"></i> Dipinjam</span>
                                    @break
                                @case('maintenance')
                                    <span class="badge rounded-pill bg-danger shadow-sm px-3 py-2"><i class="fas fa-tools me-1"></i> Maintenance</span>
                                    @break
                            @endswitch
                            @switch($item->condition)
                                @case('baik')
                                    <span class="badge rounded-pill bg-info shadow-sm px-3 py-2"><i class="fas fa-thumbs-up me-1"></i> Kondisi Baik</span>
                                    @break
                                @case('rusak')
                                    <span class="badge rounded-pill bg-danger shadow-sm px-3 py-2"><i class="fas fa-times-circle me-1"></i> Rusak</span>
                                    @break
                                @case('dalam_perbaikan')
                                    <span class="badge rounded-pill bg-warning text-dark shadow-sm px-3 py-2"><i class="fas fa-wrench me-1"></i> Dalam Perbaikan</span>
                                    @break
                            @endswitch
                        </div>
                    </div>
                </div>

                <div class="table-responsive mb-4">
                    <table class="table table-borderless align-middle">
                        <tbody>
                            <tr class="border-bottom border-light">
                                <td width="35%" class="text-muted fw-semibold py-3 text-uppercase small tracking-wide"><i class="fas fa-barcode me-2 text-danger"></i>Barcode</td>
                                <td class="py-3"><code class="bg-light px-3 py-2 rounded-3 text-dark fw-bold border shadow-sm">{{ $item->barcode }}</code></td>
                            </tr>
                            <tr class="border-bottom border-light">
                                <td class="text-muted fw-semibold py-3 text-uppercase small tracking-wide"><i class="fas fa-tags me-2 text-danger"></i>Kategori</td>
                                <td class="py-3"><span class="badge rounded-pill shadow-sm px-3 py-2" style="background:linear-gradient(135deg,#e60000,#9b2c9b);">{{ $item->category->name }}</span></td>
                            </tr>
                            <tr class="border-bottom border-light">
                                <td class="text-muted fw-semibold py-3 text-uppercase small tracking-wide"><i class="fas fa-map-marker-alt me-2 text-danger"></i>Lokasi</td>
                                <td class="py-3"><span class="badge bg-secondary rounded-pill shadow-sm px-3 py-2">{{ $item->location->name }}</span></td>
                            </tr>
                            <tr class="border-bottom border-light">
                                <td class="text-muted fw-semibold py-3 text-uppercase small tracking-wide"><i class="fas fa-cubes me-2 text-danger"></i>Jumlah</td>
                                <td class="py-3"><span class="fw-bold fs-6 text-dark">{{ $item->quantity }}</span></td>
                            </tr>
                            <tr class="border-bottom border-light">
                                <td class="text-muted fw-semibold py-3 text-uppercase small tracking-wide"><i class="fas fa-tag me-2 text-danger"></i>Harga</td>
                                <td class="py-3"><span class="fw-bold fs-6 text-dark">Rp. {{ number_format($item->price, 2, ',', '.') }}</span></td>
                            </tr>
                            <tr class="border-bottom border-light">
                                <td class="text-muted fw-semibold py-3 text-uppercase small tracking-wide"><i class="fas fa-calendar-alt me-2 text-danger"></i>Tgl Pembelian</td>
                                <td class="py-3"><span class="fw-semibold text-dark">{{ $item->purchase_date ? \Carbon\Carbon::parse($item->purchase_date)->format('d/m/Y') : '-' }}</span></td>
                            </tr>
                            @if($item->description)
                            <tr class="border-bottom border-light">
                                <td class="text-muted fw-semibold py-3 text-uppercase small tracking-wide"><i class="fas fa-align-left me-2 text-danger"></i>Deskripsi</td>
                                <td class="py-3">{{ $item->description }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td class="text-muted fw-semibold py-3 text-uppercase small tracking-wide"><i class="fas fa-history me-2 text-danger"></i>Total Peminjaman</td>
                                <td class="py-3"><span class="badge bg-danger rounded-pill shadow-sm px-3 py-2">{{ $item->borrowings->count() }}x dipinjam</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-4 border-top">
                    <a href="{{ route('items.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm fw-semibold hover-lift">
                        <i class="fas fa-arrow-left me-1 text-muted"></i> Kembali
                    </a>
                    <div class="d-flex gap-2">
                        <a href="{{ route('items.qrcode', $item->id) }}" class="btn btn-dark rounded-pill px-4 shadow-sm fw-semibold hover-lift">
                            <i class="fas fa-qrcode me-1"></i> QR Code
                        </a>
                        <a href="{{ route('items.edit', $item) }}" class="btn btn-warning rounded-pill px-4 shadow-sm fw-semibold hover-lift text-dark">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                        <form action="{{ route('items.destroy', $item) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger rounded-pill px-4 shadow-sm fw-semibold hover-lift"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">
                                <i class="fas fa-trash me-1"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        {{-- Riwayat Peminjaman --}}
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden sticky-top" style="top: 20px;">
            <div class="card-header border-0 py-4" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-history me-2 text-danger"></i>Riwayat Peminjaman
                    </h6>
                    <span class="badge bg-danger rounded-pill shadow-sm">{{ $item->borrowings->count() }}x</span>
                </div>
            </div>
            <div class="card-body p-0">
                @if($item->borrowings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 px-4 py-3 text-uppercase small fw-bold tracking-wide text-muted">Peminjam</th>
                                    <th class="border-0 px-4 py-3 text-uppercase small fw-bold tracking-wide text-muted">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($item->borrowings as $borrowing)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="fw-semibold text-dark">{{ $borrowing->borrower_name }}</div>
                                            <div class="small text-muted"><i class="far fa-calendar-alt me-1"></i>{{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('d/m/y') }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($borrowing->status == 'dikembalikan')
                                                <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle shadow-sm">Dikembalikan</span>
                                            @elseif($borrowing->status == 'terlambat')
                                                <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle shadow-sm">Terlambat</span>
                                            @else
                                                <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle shadow-sm">{{ ucfirst($borrowing->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div style="width:60px;height:60px;border-radius:50%;background:#f8f9fa;display:inline-flex;align-items:center;justify-content:center;margin-bottom:15px;">
                            <i class="fas fa-inbox fa-2x text-muted opacity-50"></i>
                        </div>
                        <p class="text-muted fw-semibold mb-0">Belum ada riwayat</p>
                        <p class="small text-muted opacity-75">Barang ini belum pernah dipinjam.</p>
                    </div>
                @endif
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
.bg-success-subtle { background-color: #d1e7dd !important; }
.bg-danger-subtle { background-color: #f8d7da !important; }
.bg-warning-subtle { background-color: #fff3cd !important; }
.text-success { color: #0f5132 !important; }
.text-danger { color: #842029 !important; }
.text-warning { color: #664d03 !important; }
</style>
@endsection
