@extends('layouts.app')

@section('title', 'Peminjaman Saya')

@section('content')
<style>
    .modern-card {
        border-radius: 18px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        border: none;
    }
    .modern-card-header {
        background: white;
        border-radius: 18px 18px 0 0 !important;
        border-bottom: 1px solid rgba(0,0,0,0.04);
        padding: 1.5rem;
    }
    .table-hover tbody tr {
        transition: all 0.2s ease;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(230,0,0,0.02) !important;
    }
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.85rem;
    }
</style>

<div class="container-fluid px-0 fade-in">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="page-header py-4" style="background: linear-gradient(135deg, #ffffff 0%, #fefefe 100%); border-radius: 18px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
                <div class="d-flex justify-content-between align-items-center px-4">
                    <div>
                        <h4 class="mb-1 fw-bold text-dark"><i class="fas fa-list me-2 text-primary"></i>Riwayat Peminjaman Saya</h4>
                        <p class="text-muted mb-0">Pantau status persetujuan, barang terlambat, dan histori pengembalian</p>
                    </div>
                    <a href="{{ route('user.dashboard') }}" class="btn btn-light border shadow-sm rounded-pill px-4 fw-medium text-dark">
                        <i class="fas fa-arrow-left me-2"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row fade-in" style="animation-delay: 0.1s;">
        <div class="col-md-12">
            <div class="card modern-card overflow-hidden">
                <div class="card-header modern-card-header d-flex align-items-center justify-content-between bg-white border-bottom">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-history text-success me-2"></i> Data Peminjaman</h5>
                    @if($loans->count() > 0)
                        <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill">{{ $loans->count() }} Transaksi</span>
                    @endif
                </div>
                <div class="card-body p-0">
                    @if($loans->count() > 0)
                        <form action="{{ route('user.loans.bulk-return') }}" method="POST" id="bulkReturnForm" enctype="multipart/form-data" class="p-4 bg-light border-bottom">
                            @csrf
                            <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between">
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-white border shadow-sm btn-sm fw-medium text-primary px-3 rounded-pill" onclick="selectAllLoans()">
                                        <i class="fas fa-check-square me-1"></i> Pilih Semua
                                    </button>
                                    <button type="button" class="btn btn-white border shadow-sm btn-sm fw-medium text-secondary px-3 rounded-pill" onclick="deselectAllLoans()">
                                        <i class="far fa-square me-1"></i> Batal Pilih
                                    </button>
                                </div>
                                <div class="d-flex align-items-center gap-3 bg-white p-2 border rounded-pill shadow-sm">
                                    <span class="text-muted fw-semibold small px-3" id="selectedCount">0 dipilih</span>
                                    <div class="border-start ps-3 me-2">
                                        <input type="file" name="return_photo" class="form-control form-control-sm border-0 bg-light rounded-pill px-3" accept="image/*" required style="width: 200px;" title="Foto Bukti Pengembalian">
                                    </div>
                                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-4 fw-bold shadow-sm" onclick="return confirmBulkReturn(event)">
                                        <i class="fas fa-undo me-1"></i> Kembalikan Terpilih
                                    </button>
                                </div>
                            </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 border-0" style="min-width: 1000px;">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3 border-0 text-muted fw-semibold" width="5%">
                                            <input type="checkbox" id="selectAll" onchange="toggleAll(this)" class="form-check-input shadow-sm" style="transform: scale(1.1);">
                                        </th>
                                        <th class="px-4 py-3 border-0 text-muted fw-semibold">Barang</th>
                                        <th class="px-4 py-3 border-0 text-muted fw-semibold">Status</th>
                                        <th class="px-4 py-3 border-0 text-muted fw-semibold">Tgl Pinjam</th>
                                        <th class="px-4 py-3 border-0 text-muted fw-semibold">Tenggat</th>
                                        <th class="px-4 py-3 border-0 text-muted fw-semibold">Tgl Kembali</th>
                                        <th class="px-4 py-3 border-0 text-muted fw-semibold text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($loans as $loan)
                                        <tr class="border-bottom">
                                            <td class="px-4 py-3">
                                                @if(in_array($loan->status, ['disetujui', 'approved']))
                                                    <input type="checkbox" name="loan_ids[]" value="{{ $loan->id }}" class="loan-checkbox form-check-input shadow-sm" onchange="updateSelectedCount()" style="transform: scale(1.1);">
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3 text-secondary" style="width: 40px; height: 40px;">
                                                        <i class="fas fa-box"></i>
                                                    </div>
                                                    <div class="fw-bold text-dark">{{ $loan->item->name }}</div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                @switch($loan->status)
                                                    @case('menunggu')
                                                    @case('pending')
                                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 status-badge rounded-pill"><i class="fas fa-clock me-1"></i>Menunggu</span>
                                                        @break
                                                    @case('disetujui')
                                                    @case('approved')
                                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 status-badge rounded-pill"><i class="fas fa-check-circle me-1"></i>Disetujui</span>
                                                        @break
                                                    @case('ditolak')
                                                    @case('rejected')
                                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 status-badge rounded-pill"><i class="fas fa-times-circle me-1"></i>Ditolak</span>
                                                        @break
                                                    @case('dikembalikan')
                                                    @case('returned')
                                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 status-badge rounded-pill"><i class="fas fa-box-open me-1"></i>Dikembalikan</span>
                                                        @break
                                                    @case('return_pending')
                                                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 status-badge rounded-pill"><i class="fas fa-undo me-1"></i>Menunggu Verifikasi</span>
                                                        @break
                                                    @case('terlambat')
                                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 status-badge rounded-pill"><i class="fas fa-exclamation-triangle me-1"></i>Terlambat</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">{{ $loan->status }}</span>
                                                @endswitch
                                            </td>
                                            <td class="px-4 py-3 text-muted">
                                                {{ $loan->borrow_date ? \Carbon\Carbon::parse($loan->borrow_date)->format('d M Y') : \Carbon\Carbon::parse($loan->created_at)->format('d M Y') }}
                                            </td>
                                            <td class="px-4 py-3 fw-medium {{ $loan->expected_return_date && in_array($loan->status, ['disetujui', 'approved']) && \Carbon\Carbon::parse($loan->expected_return_date)->isPast() ? 'text-danger' : 'text-dark' }}">
                                                {{ $loan->expected_return_date ? \Carbon\Carbon::parse($loan->expected_return_date)->format('d M Y') : '-' }}
                                            </td>
                                            <td class="px-4 py-3 text-muted">{{ $loan->returned_at ? \Carbon\Carbon::parse($loan->returned_at)->format('d M Y') : '-' }}</td>
                                            <td class="px-4 py-3 text-end">
                                                @if(in_array($loan->status, ['disetujui', 'approved']))
                                                    <!-- Keep button trigger for single return form action - form moved outside table -> not possible easy. Since we just style, let's keep form inside. But it might break table layouts with nested forms within row if not careful. The original code has forms inside td -->
                                                    <form action="{{ route('user.return', $loan) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 shadow-sm" onclick="return confirm('Apakah Anda yakin ingin mengembalikan barang ini?')">
                                                            <i class="fas fa-undo me-1"></i> Kembali
                                                        </button>
                                                    </form>
                                                @elseif(in_array($loan->status, ['menunggu', 'pending']))
                                                    <span class="badge bg-light text-muted border px-2 py-1"><i class="fas fa-hourglass-half me-1"></i>Menunggu</span>
                                                @elseif(in_array($loan->status, ['ditolak', 'rejected']))
                                                    <span class="badge bg-light text-danger border px-2 py-1"><i class="fas fa-ban me-1"></i>Ditolak</span>
                                                @elseif(in_array($loan->status, ['dikembalikan', 'returned']))
                                                    <span class="badge bg-light text-success border px-2 py-1"><i class="fas fa-check me-1"></i>Selesai</span>
                                                @elseif($loan->status == 'return_pending')
                                                    <span class="badge bg-light text-info border px-2 py-1"><i class="fas fa-undo me-1"></i>Verifikasi</span>
                                                @elseif($loan->status == 'terlambat')
                                                    <span class="badge bg-light text-danger border px-2 py-1 fw-bold"><i class="fas fa-exclamation me-1"></i>Segera Kembalikan</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        </form>
                    @else
                        <div class="text-center py-5">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
                                <i class="fas fa-receipt fa-3x text-muted opacity-50"></i>
                            </div>
                            <h4 class="fw-bold text-dark mb-2">Riwayat Kosong</h4>
                            <p class="text-muted mb-4">Anda belum pernah melakukan peminjaman barang.</p>
                            <a href="{{ route('user.borrow') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-medium">
                                <i class="fas fa-search-plus me-2"></i>Mulai Pinjam Barang
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function toggleAll(source) {
    const checkboxes = document.querySelectorAll('.loan-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = source.checked;
    });
    updateSelectedCount();
}

function selectAllLoans() {
    const checkboxes = document.querySelectorAll('.loan-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
    document.getElementById('selectAll').checked = true;
    updateSelectedCount();
}

function deselectAllLoans() {
    const checkboxes = document.querySelectorAll('.loan-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    document.getElementById('selectAll').checked = false;
    updateSelectedCount();
}

function updateSelectedCount() {
    const checkboxes = document.querySelectorAll('.loan-checkbox:checked');
    document.getElementById('selectedCount').textContent = checkboxes.length + ' dipilih';
}

function confirmBulkReturn(event) {
    const checkboxes = document.querySelectorAll('.loan-checkbox:checked');
    if (checkboxes.length === 0) {
        event.preventDefault();
        alert('Silakan pilih setidaknya satu barang untuk dikembalikan!');
        return false;
    }
    if (!confirm('Apakah Anda yakin ingin mengembalikan ' + checkboxes.length + ' barang?')) {
        event.preventDefault();
        return false;
    }
    return true;
}
</script>
@endsection
