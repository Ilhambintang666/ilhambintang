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
            <div class="col-md-3">
                <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Kondisi</label>
                <select name="condition" class="form-select border-light bg-light" style="border-radius: 10px; padding: 10px 15px;">
                    <option value="">Semua Kondisi</option>
                    <option value="baik" {{ request('condition') == 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak" {{ request('condition') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                    <option value="dalam_perbaikan" {{ request('condition') == 'dalam_perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Pencarian Barang</label>
                <div class="input-group">
                    <span class="input-group-text border-light bg-light" style="border-radius: 10px 0 0 10px; border-right: none;">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-light bg-light" 
                           placeholder="Cari berdasarkan nama atau barcode..." value="{{ request('search') }}" style="border-radius: 0 10px 10px 0; border-left: none; padding: 10px 15px;">
                </div>
            </div>
            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-dark w-100 shadow-sm" style="border-radius: 10px; padding: 10px 15px; font-weight: 500;">
                    Filter Data
                </button>
                <a href="{{ route('items.index') }}" class="btn btn-light border-light shadow-sm" title="Reset Filter" style="border-radius: 10px; padding: 10px 15px;">
                    <i class="fas fa-redo text-muted"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Custom Tab Navigation & Lokasi Cepat --}}
<div class="mb-4">
    <ul class="nav nav-pills custom-tabs d-flex gap-2" role="tablist">
        <li class="nav-item">
            <button class="nav-link active item-tab-btn shadow-sm" data-tab="all" type="button">
                <i class="fas fa-layer-group me-2"></i>Semua Barang
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link item-tab-btn shadow-sm" data-tab="1" type="button">
                <i class="fas fa-hand-holding me-2 text-success"></i>Barang Pinjaman
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link item-tab-btn shadow-sm" data-tab="0" type="button">
                <i class="fas fa-cube me-2 text-secondary"></i>Aset Tetap Terpusat
            </button>
        </li>
    </ul>
    
    {{-- Location Quick Filters --}}
    <div class="mt-3 p-3 bg-white rounded-4 shadow-sm border" style="border-color: rgba(0,0,0,0.08) !important;">
        <p class="text-muted small fw-bold text-uppercase mb-2" style="letter-spacing: 0.5px;"><i class="fas fa-map-marker-alt me-2 text-danger"></i>Filter Lokasi Cepat</p>
        <div class="d-flex flex-wrap gap-2">
            <button class="btn btn-sm loc-pill active shadow-sm" data-loc="all">Semua Lokasi</button>
            @foreach($locations as $loc)
                <button class="btn btn-sm btn-light border loc-pill text-muted hover-shadow" data-loc="{{ $loc->id }}">{{ $loc->name }}</button>
            @endforeach
        </div>
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
                Tampil: <span id="visibleItemCount">{{ $items->count() }}</span> / Total: {{ $items->count() }}
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
                            <th class="text-uppercase text-muted" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; padding: 16px; border-bottom: 2px solid #eee;">Info Detail Barang</th>
                            <th class="text-uppercase text-muted" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; padding: 16px; border-bottom: 2px solid #eee;">Status & Kondisi</th>
                            <th class="text-uppercase text-muted text-center" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; padding: 16px; border-bottom: 2px solid #eee;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $index => $item)
                        <tr style="transition: all 0.2s; background: #fff;" class="hover-row item-row" data-is-loanable="{{ $item->is_loanable ? '1' : '0' }}" data-location-id="{{ $item->location_id }}">
                            <td class="text-center" style="border-bottom: 1px solid #f0f0f0;">
                                <input type="checkbox" class="form-check-input item-checkbox" value="{{ $item->id }}" style="cursor: pointer; transform: scale(1.2);">
                            </td>
                            <td style="border-bottom: 1px solid #f0f0f0;">
                                <span class="text-muted fw-bold serial-number">{{ $index + 1 }}</span>
                            </td>
                            <td style="border-bottom: 1px solid #f0f0f0;">
                                <div class="d-flex align-items-start">
                                    <div class="bg-light rounded p-2 me-3 d-flex align-items-center justify-content-center border" style="width: 50px; height: 50px; min-width: 50px;">
                                        @if($item->is_loanable)
                                            <i class="fas fa-hand-holding text-success" style="font-size: 1.5rem;" title="Barang Pinjaman"></i>
                                        @else
                                            <i class="fas fa-cube text-secondary" style="font-size: 1.5rem;" title="Aset Tetap"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold" style="color: #2c3e50; font-size: 1.05rem;">
                                            {{ $item->name }}
                                            @if($item->is_loanable)
                                                <span class="badge rounded-pill bg-success ms-2 pb-1" style="font-size: 0.65rem;">Pinjaman</span>
                                            @else
                                                <span class="badge rounded-pill ms-2 pb-1" style="background-color: #6c757d; font-size: 0.65rem;">Aset Tetap</span>
                                            @endif
                                        </h6>
                                        <div class="d-flex align-items-center mb-2">
                                            <code class="bg-light text-secondary px-2 py-0 rounded border small me-2" style="font-size: 0.75rem;">{{ $item->barcode }}</code>
                                            <span class="text-muted small"><strong>{{ $item->total_sejenis }}</strong> unit tersedia di sistem</span>
                                        </div>
                                        <div class="d-flex flex-wrap gap-2 mt-1">
                                            <span class="badge bg-light text-dark border rounded-pill px-2 py-1 shadow-sm" style="font-size: 0.7rem;">
                                                <i class="fas fa-tags text-warning me-1"></i> {{ $item->category->name }}
                                            </span>
                                            <span class="badge bg-light text-dark border rounded-pill px-2 py-1 shadow-sm" style="font-size: 0.7rem;">
                                                <i class="fas fa-map-marker-alt text-info me-1"></i> {{ $item->location->name }}
                                            </span>
                                        </div>
                                    </div>
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

/* Custom Tab Navigation */
.custom-tabs .nav-link {
    border-radius: 12px;
    color: #495057 !important;
    background: #fff;
    border: 1px solid rgba(0,0,0,0.08);
    padding: 10px 20px;
    font-weight: 600;
    transition: all 0.3s ease;
}
.custom-tabs .nav-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    color: #212529 !important;
}
.custom-tabs .nav-link.active {
    background: linear-gradient(135deg, #e60000, #b20000) !important;
    color: #fff !important;
    border: none;
}
.custom-tabs .nav-link.active i {
    color: #fff !important;
}

/* Location Pills */
.loc-pill {
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.8rem;
    padding: 6px 16px;
    transition: all 0.2s ease;
    background: #fff;
}
.loc-pill.active {
    background: linear-gradient(135deg, #2c3e50, #1a252f) !important;
    color: #fff !important;
    border-color: #2c3e50 !important;
    box-shadow: 0 4px 10px rgba(44, 62, 80, 0.3) !important;
}
.loc-pill:hover:not(.active) {
    background: #f8f9fa;
    transform: translateY(-1px);
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
            // Only check visible ones
            document.querySelectorAll('.item-row').forEach(row => {
                if(row.style.display !== 'none') {
                    const cb = row.querySelector('.item-checkbox');
                    if(cb) cb.checked = this.checked;
                }
            });
            updateBulkDeleteButton();
        });
    }

    itemCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkDeleteButton);
    });

    // Frontend Filtering Logic
    let currentTab = 'all';
    let currentLocation = 'all';

    function applyFilters() {
        let visibleCount = 0;
        let serialNum = 1;
        document.querySelectorAll('.item-row').forEach(row => {
            let itemLoanable = row.getAttribute('data-is-loanable');
            let itemLoc = row.getAttribute('data-location-id');

            let matchTab = (currentTab === 'all') || (itemLoanable === currentTab);
            let matchLoc = (currentLocation === 'all') || (itemLoc === currentLocation);
            
            if (matchTab && matchLoc) {
                row.style.display = '';
                // Update serial number to maintain continuity
                let serialEl = row.querySelector('.serial-number');
                if(serialEl) serialEl.textContent = serialNum++;
                visibleCount++;
            } else {
                row.style.display = 'none';
                // Uncheck if hidden
                let cb = row.querySelector('.item-checkbox');
                if(cb && cb.checked) {
                    cb.checked = false;
                }
            }
        });

        const countSpan = document.getElementById('visibleItemCount');
        if(countSpan) countSpan.textContent = visibleCount;
        updateBulkDeleteButton();
    }

    // Tab Event Listeners
    document.querySelectorAll('.item-tab-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.item-tab-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            currentTab = this.getAttribute('data-tab');
            applyFilters();
        });
    });

    // Location Pill Event Listeners
    document.querySelectorAll('.loc-pill').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.loc-pill').forEach(b => b.classList.remove('active', 'btn-danger'));
            document.querySelectorAll('.loc-pill').forEach(b => {
                if(b !== this) {
                    b.classList.add('btn-light', 'text-muted');
                }
            });
            
            this.classList.remove('btn-light', 'text-muted');
            this.classList.add('active');
            
            currentLocation = this.getAttribute('data-loc');
            applyFilters();
        });
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