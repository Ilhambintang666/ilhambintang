@extends('layouts.app')

@section('title', 'Kelola Barang')

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-end mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: #2c3e50;"><i class="fas fa-box-open me-2 text-danger"></i>Kelola Barang</h3>
        <p class="text-muted mb-0">Manajemen seluruh aset dan barang inventaris PMI</p>
    </div>
    <a href="{{ route('items.create') }}" class="btn btn-danger rounded-pill px-4 py-2 shadow-sm" style="background: linear-gradient(135deg, #e60000, #b20000); border: none; font-weight: 500;">
        <i class="fas fa-plus me-2"></i>Tambah Barang Baru
    </a>
</div>

{{-- Filter Section --}}
<div class="card border shadow-sm mb-4" style="border-radius: 16px; overflow: hidden; background: #fff; border-color: rgba(0,0,0,0.08) !important;">
    <div class="card-body p-4">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Status</label>
                <select name="status" class="form-select border-light bg-light" style="border-radius: 10px; padding: 10px 15px;">
                    <option value="">Semua Status</option>
                    <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Jenis Barang</label>
                <select name="is_loanable" class="form-select border-light bg-light" style="border-radius: 10px; padding: 10px 15px;">
                    <option value="">Semua Jenis</option>
                    <option value="1" {{ request('is_loanable') === '1' ? 'selected' : '' }}>Pinjaman</option>
                    <option value="0" {{ request('is_loanable') === '0' ? 'selected' : '' }}>Aset Tetap</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Kondisi</label>
                <select name="condition" class="form-select border-light bg-light" style="border-radius: 10px; padding: 10px 15px;">
                    <option value="">Semua Kondisi</option>
                    <option value="baik" {{ request('condition') == 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak" {{ request('condition') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                    <option value="dalam_perbaikan" {{ request('condition') == 'dalam_perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Pencarian</label>
                <div class="input-group">
                    <span class="input-group-text border-light bg-light" style="border-radius: 10px 0 0 10px; border-right: none;">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-light bg-light" 
                           placeholder="Nama/Barcode..." value="{{ request('search') }}" style="border-radius: 0 10px 10px 0; border-left: none; padding: 10px 15px;">
                </div>
            </div>
            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-dark w-100 shadow-sm" style="border-radius: 10px; padding: 10px 15px; font-weight: 500;">
                    Filter
                </button>
                <a href="{{ route('items.index') }}" class="btn btn-light border-light shadow-sm" style="border-radius: 10px; padding: 10px 15px;">
                    <i class="fas fa-redo text-muted"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Items Table --}}
{{-- Items Table --}}
<div class="card border shadow-sm" style="border-radius: 16px; overflow: hidden; background: #fff; border-color: rgba(0,0,0,0.08) !important;">
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center p-4">
        <h5 class="mb-0 fw-bold" style="color: #2c3e50;">
            <i class="fas fa-list-ul me-2 text-danger"></i>
            Daftar Barang Inventaris
        </h5>
        <div class="d-flex align-items-center gap-3">
            <button type="button" id="bulkDeleteBtn" class="btn btn-danger rounded-pill px-3 py-1 shadow-sm d-none" style="font-weight: 500;" onclick="submitBulkDelete()">
                <i class="fas fa-trash-alt me-2"></i>Hapus (<span id="selectedCount">0</span>)
            </button>
            <span class="badge rounded-pill px-3 py-2 shadow-sm" style="background: rgba(230, 0, 0, 0.1); color: #e60000; font-weight: 600; border: 1px solid rgba(230, 0, 0, 0.2);">
                Total: {{ $items->count() }} Barang
            </span>
        </div>
    </div>
    <div class="card-body p-0">
        @if($items->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="border-collapse: separate; border-spacing: 0;">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th style="width: 50px; padding: 16px; text-align: center; border-bottom: 2px solid #eee;">
                                <input type="checkbox" id="selectAllItems" class="form-check-input" style="cursor: pointer; transform: scale(1.2);">
                            </th>
                            <th class="text-uppercase text-muted" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; padding: 16px; border-bottom: 2px solid #eee;">No</th>
                            <th class="text-uppercase text-muted" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; padding: 16px; border-bottom: 2px solid #eee;">Info Barang</th>
                            <th class="text-uppercase text-muted" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; padding: 16px; border-bottom: 2px solid #eee;">Klasifikasi</th>
                            <th class="text-uppercase text-muted" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; padding: 16px; border-bottom: 2px solid #eee;">Jenis</th>
                            <th class="text-uppercase text-muted" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; padding: 16px; border-bottom: 2px solid #eee;">Status & Kondisi</th>
                            <th class="text-uppercase text-muted text-center" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; padding: 16px; border-bottom: 2px solid #eee;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $index => $item)
                        <tr style="transition: all 0.2s; background: #fff;" class="hover-row">
                            <td class="text-center" style="border-bottom: 1px solid #f0f0f0;">
                                <input type="checkbox" class="form-check-input item-checkbox" value="{{ $item->id }}" style="cursor: pointer; transform: scale(1.2);">
                            </td>
                            <td style="border-bottom: 1px solid #f0f0f0;">
                                <span class="text-muted fw-bold">{{ $index + 1 }}</span>
                            </td>
                            <td style="border-bottom: 1px solid #f0f0f0; max-width: 250px;">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded p-2 me-3 d-flex align-items-center justify-content-center border" style="width: 48px; height: 48px; min-width: 48px;">
                                        <i class="fas fa-box text-danger" style="font-size: 1.25rem;"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-truncate" style="color: #2c3e50; max-width: 200px;" title="{{ $item->name }}">{{ $item->name }}</h6>
                                        <div class="d-flex align-items-center mt-1">
                                            <code class="bg-light text-secondary px-2 py-0 rounded border small me-2" style="font-size: 0.75rem;">{{ $item->barcode }}</code>
                                            @if($item->description)
                                                <small class="text-muted text-truncate d-inline-block" style="max-width: 120px;" title="{{ $item->description }}">{{ $item->description }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="border-bottom: 1px solid #f0f0f0;">
                                <div class="d-flex flex-column gap-1">
                                    <span class="badge bg-light text-dark border rounded-pill px-2 py-1 align-self-start shadow-sm" style="font-size: 0.7rem;">
                                        <i class="fas fa-folder text-warning me-1"></i> {{ $item->category->name }}
                                    </span>
                                    <span class="badge bg-light text-dark border rounded-pill px-2 py-1 align-self-start shadow-sm" style="font-size: 0.7rem;">
                                        <i class="fas fa-map-marker-alt text-info me-1"></i> {{ $item->location->name }}
                                    </span>
                                </div>
                            </td>
                            <td style="border-bottom: 1px solid #f0f0f0;">
                                @if($item->is_loanable)
                                    <span class="badge rounded-pill px-3 py-1" style="background: rgba(25, 135, 84, 0.1); color: #198754; font-weight: 600;">
                                        <i class="fas fa-hand-holding me-1"></i> Pinjaman
                                    </span>
                                @else
                                    <span class="badge rounded-pill px-3 py-1" style="background: rgba(108, 117, 125, 0.1); color: #6c757d; font-weight: 600;">
                                        <i class="fas fa-cube me-1"></i> Aset Tetap
                                    </span>
                                @endif
                                <div class="mt-2 small">
                                    <span class="text-muted">Unit: </span><strong class="text-dark">{{ $item->total_sejenis }}</strong>
                                </div>
                            </td>
                            <td style="border-bottom: 1px solid #f0f0f0;">
                                <div class="d-flex flex-column gap-2 align-items-start">
                                    @switch($item->status)
                                        @case('tersedia')
                                            <span class="badge rounded-pill px-3 py-1 shadow-sm" style="background: linear-gradient(135deg, #28a745, #20c997);"><i class="fas fa-check-circle me-1"></i>Tersedia</span>
                                            @break
                                        @case('dipinjam')
                                            <span class="badge rounded-pill px-3 py-1 shadow-sm" style="background: linear-gradient(135deg, #ffc107, #fd7e14);"><i class="fas fa-handshake me-1"></i>Dipinjam</span>
                                            @break
                                        @case('maintenance')
                                            <span class="badge rounded-pill px-3 py-1 shadow-sm" style="background: linear-gradient(135deg, #dc3545, #e83e8c);"><i class="fas fa-tools me-1"></i>Maintenance</span>
                                            @break
                                    @endswitch

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
                                </div>
                            </td>
                            <td class="text-center" style="border-bottom: 1px solid #f0f0f0;">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('items.show', $item) }}" class="action-btn view-btn" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('items.edit', $item) }}" class="action-btn edit-btn" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('items.qrcode', $item->id) }}" class="action-btn qrcode-btn" title="QR Code">
                                        <i class="fas fa-qrcode"></i>
                                    </a>
                                    <form action="{{ route('items.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus barang {{ $item->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn delete-btn" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-3 mb-3">
                @if(method_exists($items, 'links'))
                    {{ $items->links() }}
                @endif
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px; border-radius: 50%; background: rgba(230,0,0,0.05);">
                        <i class="fas fa-box-open fa-3x" style="color: #e60000;"></i>
                    </div>
                </div>
                <h5 class="fw-bold" style="color: #2c3e50;">Data Barang Kosong</h5>
                <p class="text-muted mb-4">Belum ada barang yang didaftarkan dalam sistem.</p>
                <a href="{{ route('items.create') }}" class="btn btn-danger rounded-pill px-4 py-2 shadow-sm" style="background: linear-gradient(135deg, #e60000, #b20000); border: none;">
                    <i class="fas fa-plus me-2"></i>Tambah Barang Pertama
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<style>
/* Modern Elegant Styles */
.hover-row:hover {
    background-color: #fcfcfc !important;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    z-index: 10;
    position: relative;
}

.action-btn {
    width: 35px; 
    height: 35px; 
    display: inline-flex; 
    align-items: center; 
    justify-content: center; 
    border-radius: 50%;
    transition: all 0.3s ease;
    text-decoration: none;
    background: #fff;
    border: 1px solid #eaeaea;
    color: #6c757d;
}

.view-btn:hover { background: #e0f4ff; color: #007bff; border-color: #b8e2ff; transform: translateY(-3px); box-shadow: 0 4px 8px rgba(0,123,255,0.15); }
.edit-btn:hover { background: #fff8e5; color: #ffc107; border-color: #ffeeba; transform: translateY(-3px); box-shadow: 0 4px 8px rgba(255,193,7,0.15); }
.qrcode-btn:hover { background: #f4f6f8; color: #343a40; border-color: #dae0e5; transform: translateY(-3px); box-shadow: 0 4px 8px rgba(52,58,64,0.15); }
.delete-btn { border: 1px solid #eaeaea; background: #fff; padding: 0; }
.delete-btn:hover { background: #ffebee; color: #dc3545; border-color: #ffcdd2; transform: translateY(-3px); box-shadow: 0 4px 8px rgba(220,53,69,0.15); }

/* Custom Checkbox */
.form-check-input:checked {
    background-color: #e60000;
    border-color: #e60000;
}

select.form-select:focus, input.form-control:focus {
    box-shadow: 0 0 0 0.25rem rgba(230, 0, 0, 0.15);
    border-color: #e60000 !important;
}
</style>

<form id="bulkDeleteForm" action="{{ route('items.bulk-destroy') }}" method="POST" style="display: none;">
    @csrf
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAllItems');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const selectedCountSpan = document.getElementById('selectedCount');

    function updateBulkDeleteButton() {
        const checkedCount = document.querySelectorAll('.item-checkbox:checked').length;
        selectedCountSpan.textContent = checkedCount;
        
        if (checkedCount > 0) {
            bulkDeleteBtn.classList.remove('d-none');
        } else {
            bulkDeleteBtn.classList.add('d-none');
        }

        // update selectAll state
        if(selectAllCheckbox) {
            if (checkedCount === 0) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            } else if (checkedCount === itemCheckboxes.length && itemCheckboxes.length > 0) {
                selectAllCheckbox.checked = true;
                selectAllCheckbox.indeterminate = false;
            } else {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = true;
            }
        }
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            itemCheckboxes.forEach(cb => cb.checked = this.checked);
            updateBulkDeleteButton();
        });
    }

    itemCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkDeleteButton);
    });
});

function submitBulkDelete() {
    const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
    if (checkedBoxes.length === 0) return;

    if (confirm(`Peringatan: Anda akan menghapus ${checkedBoxes.length} barang sekaligus.\nTindakan ini tidak dapat dibatalkan.\n\nApakah Anda yakin ingin melanjutkan?`)) {
        const form = document.getElementById('bulkDeleteForm');
        // Clear any old hidden inputs
        form.querySelectorAll('input[name="item_ids[]"]').forEach(el => el.remove());
        
        checkedBoxes.forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'item_ids[]';
            input.value = cb.value;
            form.appendChild(input);
        });
        form.submit();
    }
}
</script>
@endsection