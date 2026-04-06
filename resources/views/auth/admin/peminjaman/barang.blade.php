@extends('layouts.app')

@section('title', 'Laporan Peminjaman')

@section('content')
<div class="container-fluid">

    {{-- JUDUL --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h4 class="fw-bold mb-3">
                📊 LAPORAN PEMINJAMAN BARANG
            </h4>
            <p class="text-muted mb-0">Filter berdasarkan periode untuk melihat laporan barang masuk dan keluar</p>

            {{-- FILTER --}}
            <form method="GET" class="row g-2 align-items-end mt-3">
                <div class="col-md-3">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="dari" class="form-control" value="{{ $dari }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="sampai" class="form-control" value="{{ $sampai }}">
                </div>

                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary">
                        🔍 Filter
                    </button>

                    <a href="{{ route('admin.laporan.barang.excel', ['dari' => $dari, 'sampai' => $sampai]) }}"
                       class="btn btn-success">
                        📗 Export Excel
                    </a>

                    <a href="{{ route('admin.laporan.barang.pdf', ['dari' => $dari, 'sampai' => $sampai]) }}"
                       class="btn btn-danger">
                        📕 Export PDF
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- RINGKASAN --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card stat-card h-100" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">{{ $barangKeluar->count() }}</h2>
                        <p class="mb-0 opacity-75">Barang Dipinjam</p>
                    </div>
                    <i class="fas fa-arrow-right stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card h-100" style="background: linear-gradient(135deg, #43e97b, #38f9d7);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">{{ $barangMasuk->count() }}</h2>
                        <p class="mb-0 opacity-75">Barang Dikembalikan</p>
                    </div>
                    <i class="fas fa-arrow-left stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card h-100" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">{{ $barangKeluar->count() - $barangMasuk->count() }}</h2>
                        <p class="mb-0 opacity-75">Belum Kembali</p>
                    </div>
                    <i class="fas fa-clock stat-icon"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- BARANG KELUAR (PEMINJAMAN) --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">📤 BARANG KELUAR (Peminjaman)</h5>

            @if($barangKeluar->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-danger">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($barangKeluar as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->tanggal }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>{{ $item->jumlah }}</td>
                            <td>{{ $item->keterangan }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-4">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <p class="text-muted">Tidak ada data peminjaman pada periode ini</p>
            </div>
            @endif
        </div>
    </div>

    {{-- BARANG MASUK (PENGEMBALIAN) --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="fw-bold mb-3">📥 BARANG MASUK (Pengembalian)</h5>

            @if($barangMasuk->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-success">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($barangMasuk as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->tanggal }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>{{ $item->jumlah }}</td>
                            <td>{{ $item->keterangan }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-4">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <p class="text-muted">Tidak ada data pengembalian pada periode ini</p>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection
