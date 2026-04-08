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
                        <h2 class="mb-1">{{ $barangDipinjam->count() }}</h2>
                        <p class="mb-0 opacity-75">1. Pre-Dipinjam</p>
                    </div>
                    <i class="fas fa-arrow-right stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card h-100" style="background: linear-gradient(135deg, #43e97b, #38f9d7);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">{{ $barangDikembalikan->count() }}</h2>
                        <p class="mb-0 opacity-75">2. Pengembalian</p>
                    </div>
                    <i class="fas fa-arrow-left stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card h-100" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">{{ $barangBaruMasuk->count() }}</h2>
                        <p class="mb-0 opacity-75">3. Aset Ditambahkan</p>
                    </div>
                    <i class="fas fa-box-open stat-icon"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- TAB TAMPILAN LAPORAN --}}
    <ul class="nav nav-tabs mb-4 px-2" id="reportTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold px-4 py-3" id="tab-pinjam" data-bs-toggle="tab" data-bs-target="#content-pinjam" type="button" role="tab" style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <i class="fas fa-arrow-right me-2 text-danger"></i> 1. Lap. Peminjaman
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold px-4 py-3" id="tab-kembali" data-bs-toggle="tab" data-bs-target="#content-kembali" type="button" role="tab" style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <i class="fas fa-arrow-left me-2 text-success"></i> 2. Lap. Pengembalian
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold px-4 py-3" id="tab-masuk" data-bs-toggle="tab" data-bs-target="#content-masuk" type="button" role="tab" style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <i class="fas fa-box me-2 text-info"></i> 3. Lap. Barang Baru (Aset)
            </button>
        </li>
    </ul>

    <div class="tab-content" id="reportTabsContent">
        
        {{-- TAB 1: PEMINJAMAN --}}
        <div class="tab-pane fade show active" id="content-pinjam" role="tabpanel">
            <div class="card shadow-sm border-0 mb-4 rounded-4" style="border-top-left-radius: 0 !important;">
                <div class="card-body">
                    <h5 class="fw-bold mb-3"><i class="fas fa-list text-danger me-2"></i> Laporan Riwayat Peminjaman</h5>

                    @if($barangDipinjam->count() > 0)
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
                                @foreach($barangDipinjam as $index => $item)
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
                    <div class="text-center py-4 bg-light rounded px-3">
                        <i class="fas fa-inbox fa-3x text-muted mb-3 opacity-50"></i>
                        <p class="text-muted fw-semibold">Tidak ada data peminjaman tertulis pada periode ini</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- TAB 2: PENGEMBALIAN --}}
        <div class="tab-pane fade" id="content-kembali" role="tabpanel">
            <div class="card shadow-sm border-0 mb-4 rounded-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3"><i class="fas fa-check-circle text-success me-2"></i> Laporan Riwayat Pengembalian</h5>

                    @if($barangDikembalikan->count() > 0)
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
                                @foreach($barangDikembalikan as $index => $item)
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
                    <div class="text-center py-4 bg-light rounded px-3">
                        <i class="fas fa-inbox fa-3x text-muted mb-3 opacity-50"></i>
                        <p class="text-muted fw-semibold">Tidak ada data pengembalian tertulis pada periode ini</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- TAB 3: BARANG MASUK / DITAMBAHKAN --}}
        <div class="tab-pane fade" id="content-masuk" role="tabpanel">
            <div class="card shadow-sm border-0 mb-4 rounded-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3"><i class="fas fa-boxes text-info me-2"></i> Laporan Penambahan Barang / Aset Baru</h5>

                    @if($barangBaruMasuk->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-info">
                                <tr>
                                    <th>No</th>
                                    <th>Tgl. Pembelian/Dibuat</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Detail & Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($barangBaruMasuk as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->tanggal }}</td>
                                    <td>{{ $item->nama_barang }}</td>
                                    <td>{{ $item->jumlah }}</td>
                                    <td>{!! nl2br(e($item->keterangan)) !!}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4 bg-light rounded px-3">
                        <i class="fas fa-inbox fa-3x text-muted mb-3 opacity-50"></i>
                        <p class="text-muted fw-semibold">Tidak ada data barang baru yang ditambahkan pada periode ini</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
