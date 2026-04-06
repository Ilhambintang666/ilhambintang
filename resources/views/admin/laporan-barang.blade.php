@extends('layouts.app')

@section('title', 'Laporan Barang - PMI Inventaris')

@section('content')
{{-- Page Header --}}
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h3><i class="fas fa-file-alt me-2 text-danger"></i>Laporan Barang</h3>
        <p class="text-muted mb-0 small">Laporan lengkap data inventaris PMI</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.laporan.barang.pdf') }}" class="btn btn-outline-danger" target="_blank">
            <i class="fas fa-file-pdf me-1"></i> Export PDF
        </a>
        <a href="{{ route('admin.laporan.barang.excel') }}" class="btn btn-success">
            <i class="fas fa-file-excel me-1"></i> Export Excel
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2 text-danger"></i>Data Laporan Barang
                </h5>
                <span class="badge bg-danger rounded-pill">{{ $items->count() }} Data</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr style="border-bottom: 2px solid #e60000;">
                                <th style="font-size:0.82rem; text-transform:uppercase; letter-spacing:.5px; padding:14px 12px; color:#333; font-weight:600;">No</th>
                                <th style="font-size:0.82rem; text-transform:uppercase; letter-spacing:.5px; padding:14px 12px; color:#333; font-weight:600;">Nama Barang</th>
                                <th style="font-size:0.82rem; text-transform:uppercase; letter-spacing:.5px; padding:14px 12px; color:#333; font-weight:600;">Kategori</th>
                                <th style="font-size:0.82rem; text-transform:uppercase; letter-spacing:.5px; padding:14px 12px; color:#333; font-weight:600;">Lokasi</th>
                                <th style="font-size:0.82rem; text-transform:uppercase; letter-spacing:.5px; padding:14px 12px; color:#333; font-weight:600;">Status</th>
                                <th style="font-size:0.82rem; text-transform:uppercase; letter-spacing:.5px; padding:14px 12px; color:#333; font-weight:600;">Kode Barcode</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td><strong>{{ $item->name }}</strong></td>
                                <td><span class="badge" style="background:linear-gradient(135deg,#e60000,#9b2c9b);">{{ $item->category->name ?? '-' }}</span></td>
                                <td><span class="badge bg-secondary">{{ $item->location->name ?? '-' }}</span></td>
                                <td>
                                    @if($item->status == 'tersedia')
                                        <span class="badge bg-success">Tersedia</span>
                                    @elseif($item->status == 'dipinjam')
                                        <span class="badge bg-warning">Dipinjam</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $item->status }}</span>
                                    @endif
                                </td>
                                <td><code class="bg-light px-2 py-1 rounded">{{ $item->barcode ?? '-' }}</code></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
