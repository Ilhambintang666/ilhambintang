@extends('layouts.app')

@section('title', 'Kelola Peminjaman')

@section('content')
{{-- Page Header --}}
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h3><i class="fas fa-history me-2 text-danger"></i>Buku Induk Transaksi Peminjaman</h3>
        <p class="text-muted mb-0 small">Daftar riwayat seluruh peminjaman berjalan, selesai, maupun pengembalian manual</p>
    </div>
</div>

{{-- Filter Section --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 py-3">
        <h6 class="mb-0"><i class="fas fa-filter me-2 text-danger"></i>Filter & Pencarian</h6>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                    <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Peminjam</label>
                <select name="borrower" class="form-select">
                    <option value="">Semua Peminjam</option>
                    @if(isset($borrowerNames))
                        @foreach($borrowerNames as $name)
                            <option value="{{ $name }}" {{ request('borrower') == $name ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Kategori</label>
                <select name="category" class="form-select">
                    <option value="">Semua Kategori</option>
                    @if(isset($categories))
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Tanggal Pinjam</label>
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Pencarian</label>
                <input type="text" name="search" class="form-control"
                       placeholder="Cari peminjam/barang..." value="{{ request('search') }}">
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-danger w-100">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Borrowings Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0">
            <i class="fas fa-list me-2 text-danger"></i>Daftar Peminjaman & Pengembalian
        </h5>
        <span class="badge bg-danger rounded-pill">{{ $borrowings->count() }} Data</span>
    </div>
    <div class="card-body p-0">
        @if($borrowings->count() > 0)
            <form action="{{ route('borrowings.bulk-return') }}" method="POST" id="bulkReturnForm">
                @csrf
                {{-- Bulk Actions --}}
                <div class="px-4 py-3 border-bottom bg-light d-flex align-items-center gap-2 flex-wrap">
                    <span class="text-muted small me-auto"><i class="fas fa-info-circle me-1"></i> Gunakan tombol di bawah ini untuk pengembalian massal otomatis:</span>

                    {{-- Bulk by Borrower --}}
                    <div class="dropdown ms-auto">
                        <button class="btn btn-sm btn-outline-dark rounded-pill px-3 shadow-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>Per Peminjam
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius:12px;">
                            @if(isset($borrowerNames) && count($borrowerNames) > 0)
                                @foreach($borrowerNames as $name)
                                    <li>
                                        <form action="{{ route('borrowings.bulk-return-by-borrower') }}" method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Kembalikan semua barang dari peminjam {{ $name }}?')">
                                            @csrf
                                            <input type="hidden" name="borrower_name" value="{{ $name }}">
                                            <button type="submit" class="dropdown-item py-2"><i class="fas fa-user text-muted me-2"></i>{{ $name }}</button>
                                        </form>
                                    </li>
                                @endforeach
                            @else
                                <li><span class="dropdown-item text-muted fst-italic">Tidak ada data</span></li>
                            @endif
                        </ul>
                    </div>

                    {{-- Bulk by Category --}}
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-dark rounded-pill px-3 shadow-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-tags me-1"></i>Per Kategori
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius:12px;">
                            @if(isset($categories) && count($categories) > 0)
                                @foreach($categories as $category)
                                    <li>
                                        <form action="{{ route('borrowings.bulk-return-by-category') }}" method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Kembalikan semua barang dari kategori {{ $category->name }}?')">
                                            @csrf
                                            <input type="hidden" name="category_id" value="{{ $category->id }}">
                                            <button type="submit" class="dropdown-item py-2"><i class="fas fa-tag text-muted me-2"></i>{{ $category->name }}</button>
                                        </form>
                                    </li>
                                @endforeach
                            @else
                                <li><span class="dropdown-item text-muted fst-italic">Tidak ada data</span></li>
                            @endif
                        </ul>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr style="border-bottom: 2px solid #e60000;">
                                <th style="font-size:0.82rem;text-transform:uppercase;letter-spacing:.5px;padding:16px 12px;color:#333;font-weight:600;" width="5%">No</th>
                                <th style="font-size:0.82rem;text-transform:uppercase;letter-spacing:.5px;padding:16px 12px;color:#333;font-weight:600;">Peminjam</th>
                                <th style="font-size:0.82rem;text-transform:uppercase;letter-spacing:.5px;padding:16px 12px;color:#333;font-weight:600;">Barang</th>
                                <th style="font-size:0.82rem;text-transform:uppercase;letter-spacing:.5px;padding:16px 12px;color:#333;font-weight:600;">Durasi & Tanggal</th>
                                <th style="font-size:0.82rem;text-transform:uppercase;letter-spacing:.5px;padding:16px 12px;color:#333;font-weight:600;" class="text-center">Status</th>
                                <th style="font-size:0.82rem;text-transform:uppercase;letter-spacing:.5px;padding:16px 12px;color:#333;font-weight:600;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @foreach($borrowings as $index => $borrowing)
                            <tr style="transition: all 0.2s;" class="hover-shadow-row">
                                <td><span class="text-muted fw-bold">{{ $index + 1 }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3 shadow-sm"
                                             style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#e60000,#9b2c9b);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.1rem;font-weight:700;flex-shrink:0;">
                                            {{ strtoupper(substr($borrowing->borrower_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong class="d-block mb-1">{{ $borrowing->borrower_name }}</strong>
                                            <span class="badge bg-light text-dark border"><i class="fas fa-user-tag me-1 text-danger"></i> Peminjam</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong class="d-block mb-1 text-dark">{{ $borrowing->item->name }}</strong>
                                    <span class="badge rounded-pill bg-light text-dark border mb-1"><i class="fas fa-barcode text-muted me-1"></i> {{ $borrowing->item->barcode }}</span>
                                    <br><span class="badge rounded-pill" style="font-size:.7rem;background:linear-gradient(135deg,#e60000,#9b2c9b);"><i class="fas fa-tag me-1"></i> {{ $borrowing->item->category->name }}</span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <div class="small"><i class="fas fa-calendar-plus text-primary me-2"></i><strong>Mulai:</strong> <span class="text-muted">{{ $borrowing->borrow_date->format('d M Y') }}</span></div>
                                        <div class="small"><i class="fas fa-calendar-check text-warning me-2"></i><strong>Target:</strong> <span class="text-muted">{{ $borrowing->expected_return_date->format('d M Y') }}</span></div>
                                        @if($borrowing->return_date)
                                            <div class="small"><i class="fas fa-undo-alt text-success me-2"></i><strong>Kembali:</strong> <span class="text-muted">{{ $borrowing->return_date->format('d M Y') }}</span></div>
                                        @endif
                                        @if($borrowing->status === 'dipinjam' && $borrowing->expected_return_date->isPast())
                                            <small class="text-white bg-danger px-2 py-1 rounded-pill d-inline-block mt-1 text-center" style="font-size: 0.7rem; max-width: max-content;">
                                                <i class="fas fa-exclamation-triangle"></i> Telat {{ $borrowing->expected_return_date->diffForHumans(null, true) }}
                                            </small>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    @switch($borrowing->status)
                                        @case('menunggu')
                                            <span class="badge bg-secondary rounded-pill px-3 py-2 shadow-sm"><i class="fas fa-clock me-1"></i> Menunggu</span>
                                            @break
                                        @case('disetujui')
                                        @case('dipinjam')
                                            @if($borrowing->expected_return_date->isPast())
                                                <span class="badge bg-danger rounded-pill px-3 py-2 shadow-sm"><i class="fas fa-times-circle me-1"></i> Terlambat</span>
                                            @else
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2 shadow-sm"><i class="fas fa-hand-holding me-1"></i> Sedang Dipinjam</span>
                                            @endif
                                            @break
                                        @case('ditolak')
                                            <span class="badge bg-danger rounded-pill px-3 py-2 shadow-sm"><i class="fas fa-ban me-1"></i> Ditolak</span>
                                            @break
                                        @case('dikembalikan')
                                            <span class="badge bg-success rounded-pill px-3 py-2 shadow-sm"><i class="fas fa-check-circle me-1"></i> Dikembalikan</span>
                                            @break
                                        @case('terlambat')
                                            <span class="badge bg-danger rounded-pill px-3 py-2 shadow-sm"><i class="fas fa-exclamation-circle me-1"></i> Telat Kembali</span>
                                            @break
                                    @endswitch
                                </td>
                                <td class="text-center">
                                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                                        @if($borrowing->status === 'menunggu')
                                            <form action="{{ route('borrowings.approve', $borrowing) }}"
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Setujui peminjaman ini?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success rounded-circle shadow-sm" style="width: 32px; height: 32px; padding: 0;" title="Setujui">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('borrowings.reject', $borrowing) }}"
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Tolak peminjaman ini?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle shadow-sm" style="width: 32px; height: 32px; padding: 0;" title="Tolak">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                                            <a href="{{ route('borrowings.show', $borrowing) }}"
                                               class="btn btn-sm btn-light border-end text-primary" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('borrowings.destroy', $borrowing) }}"
                                                  method="POST" class="d-inline m-0"
                                                  onsubmit="return confirm('Yakin ingin menghapus data peminjaman?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light text-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        @else
            <div class="text-center py-5">
                <div class="mb-3">
                    <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#e60000,#9b2c9b);display:flex;align-items:center;justify-content:center;margin:0 auto;">
                        <i class="fas fa-hand-holding fa-2x text-white"></i>
                    </div>
                </div>
                <h5 class="text-muted fw-normal">Belum ada data</h5>
                <p class="text-muted small mb-4">Belum ada data riwayat peminjaman atau pengembalian barang.</p>
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
</style>
<script>
</script>
@endsection