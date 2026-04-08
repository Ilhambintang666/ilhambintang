@extends('layouts.app')

@section('title', 'Pinjam Barang')

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
    .item-row {
        transition: all 0.2s ease;
    }
    .item-row:hover {
        background-color: rgba(230,0,0,0.02) !important;
    }
    .action-btn {
        border-radius: 12px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(230,0,0,0.15);
    }
</style>

<div class="row mb-4 fade-in">
    <div class="col-md-12">
        <div class="page-header py-4" style="background: linear-gradient(135deg, #ffffff 0%, #fefefe 100%); border-radius: 18px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
            <div class="d-flex justify-content-between align-items-center px-4">
                <div>
                    <h4 class="mb-1 fw-bold text-dark"><i class="fas fa-plus-circle me-2 text-danger"></i>Pinjam Barang Tersedia</h4>
                    <p class="text-muted mb-0">Pilih barang yang ingin dipinjam dan tentukan jadwal pengembalian</p>
                </div>
                <a href="{{ route('user.dashboard') }}" class="btn btn-light border shadow-sm rounded-pill px-4 fw-medium text-dark">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

@if($availableItems->count() > 0)
<form method="POST" action="{{ route('user.borrow.bulk') }}" id="bulkBorrowForm" class="fade-in" style="animation-delay: 0.1s;">
    @csrf
    
    <!-- Date Selection -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card modern-card border-0">
                <div class="card-body p-4 bg-light rounded-3">
                    <div class="row g-4 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark mb-2">
                                <i class="far fa-calendar-plus me-2 text-success"></i>Tanggal Mulai Pinjam <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="borrow_date" class="form-control form-control-lg border-0 shadow-sm rounded-3" 
                                   required style="font-size: 1rem;" id="borrow_date_input">
                            <small class="text-muted d-block mt-2"><i class="fas fa-info-circle me-1"></i>Kapan Anda berencana mengambil barang.</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark mb-2">
                                <i class="far fa-calendar-times me-2 text-danger"></i>Tenggat Pengembalian <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="expected_return_date" class="form-control form-control-lg border-0 shadow-sm rounded-3" 
                                   required style="font-size: 1rem;" id="return_date_input">
                            <small class="text-muted d-block mt-2"><i class="fas fa-info-circle me-1"></i>Berlaku serentak untuk semua barang terpilih.</small>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex gap-2 justify-content-md-end mt-3 mt-md-0 flex-wrap">
                                <button type="button" class="btn btn-white border border-2 text-primary action-btn bg-white" id="selectAllBtn">
                                    <i class="fas fa-check-square me-2"></i> Pilih Semua
                                </button>
                                <button type="button" class="btn btn-white border border-2 text-secondary action-btn bg-white" id="clearAllBtn">
                                    <i class="far fa-square me-2"></i> Batal Pilihan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card modern-card">
                <div class="card-header modern-card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-boxes me-2 text-success"></i>Daftar Inventaris Tersedia</h5>
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 ms-3 border border-success border-opacity-25 d-none d-sm-inline-block">{{ $availableItems->count() }} item tersedia</span>
                    </div>
                    
                    <div class="position-relative" style="max-width: 300px; width: 100%;">
                        <i class="fas fa-search position-absolute text-muted" style="top: 50%; left: 15px; transform: translateY(-50%);"></i>
                        <input type="text" id="searchItem" class="form-control rounded-pill ps-5 border-0 shadow-sm bg-light" placeholder="Cari nama barang atau barcode...">
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 border-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4 py-3 border-0" style="width: 60px;">
                                        <input type="checkbox" id="selectAll" class="form-check-input shadow-sm" style="transform: scale(1.2);">
                                    </th>
                                    <th class="px-4 py-3 border-0 text-muted fw-semibold">Detail Barang</th>
                                    <th class="px-4 py-3 border-0 text-muted fw-semibold">Kategori</th>
                                    <th class="px-4 py-3 border-0 text-muted fw-semibold">Lokasi</th>
                                    <th class="px-4 py-3 border-0 text-muted fw-semibold">Kondisi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($availableItems as $item)
                                    <tr class="item-row border-bottom">
                                        <td class="px-4 py-3">
                                            <input type="checkbox" name="items[]" value="{{ $item->id }}" 
                                                   class="form-check-input item-checkbox shadow-sm" 
                                                   data-item-name="{{ $item->name }}" style="transform: scale(1.2);">
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3 text-primary shadow-sm" style="width: 45px; height: 45px;">
                                                    <i class="fas fa-cube fs-5"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark mb-1">{{ $item->name }}</div>
                                                    @if($item->barcode)
                                                        <small class="text-muted"><i class="fas fa-barcode me-1"></i>{{ $item->barcode }}</small>
                                                    @else
                                                        <small class="text-muted"><i class="fas fa-tag me-1"></i>No Barcode</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="badge bg-light border text-dark px-2 py-1">
                                                {{ $item->category->name ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="text-muted"><i class="fas fa-map-marker-alt text-danger me-1"></i>{{ $item->location->name ?? '-' }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="badge bg-{{ $item->condition == 'baik' ? 'success' : 'warning' }} bg-opacity-10 text-{{ $item->condition == 'baik' ? 'success' : 'warning' }} border border-{{ $item->condition == 'baik' ? 'success' : 'warning' }} border-opacity-25 rounded-pill px-3 py-1 mb-1 d-inline-block">
                                                <i class="fas fa-{{ $item->condition == 'baik' ? 'check-circle' : 'exclamation-triangle' }} me-1"></i>{{ ucfirst($item->condition) }}
                                            </span>
                                            <!-- Dummy input field just for original js if needed, disabled dynamically -->
                                            <input type="hidden" name="quantities[{{ $item->id }}]" class="quantity-input" value="1" disabled data-item-id="{{ $item->id }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white p-4 border-top-0 d-flex justify-content-between align-items-center flex-wrap gap-3" style="border-radius: 0 0 18px 18px;">
                    <div class="bg-light px-4 py-2 rounded-pill border">
                        <span class="text-muted me-2">Item dipilih:</span>
                        <span id="selectedCount" class="fw-bold text-danger fs-5">0</span>
                    </div>
                    <button type="submit" class="btn btn-primary action-btn shadow-sm rounded-pill px-4 py-2" id="submitBtn" disabled>
                        <i class="fas fa-paper-plane me-2"></i> Ajukan Peminjaman
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@else
<div class="row fade-in">
    <div class="col-md-12">
        <div class="card modern-card border-0">
            <div class="card-body text-center py-5">
                <i class="fas fa-box-open text-muted opacity-50 mb-4" style="font-size: 5rem;"></i>
                <h4 class="fw-bold text-dark">Tidak Ada Barang Tersedia</h4>
                <p class="text-muted mb-4 fs-5">Maaf, saat ini seluruh inventaris sedang dipakai atau dalam tahap pemeliharaan.</p>
                <a href="{{ route('user.dashboard') }}" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm fw-medium">
                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Info Card -->
<div class="row mt-4 fade-in" style="animation-delay: 0.2s;">
    <div class="col-md-12">
        <div class="alert alert-info border-0 shadow-sm rounded-4 d-flex bg-primary bg-opacity-10 text-dark">
            <div class="text-primary fs-3 me-3 pt-1">
                <i class="fas fa-info-circle"></i>
            </div>
            <div>
                <h6 class="fw-bold text-primary mb-2">Panduan Pengajuan Peminjaman:</h6>
                <ol class="mb-0 text-muted ps-3 lh-lg" style="font-size: 0.95rem;">
                    <li>Centang kotak di sebelah kiri pada barang yang Anda butuhkan (bisa lebih dari satu).</li>
                    <li>Isi <strong>Tanggal Mulai Pinjam</strong> — kapan Anda berencana mengambil barang tersebut.</li>
                    <li>Isi <strong>Tenggat Pengembalian</strong> — berlaku serentak untuk semua barang yang Anda pilih.</li>
                    <li>Klik tombol biru <strong>"Ajukan Peminjaman"</strong> untuk mengirim pengajuan ke Admin PMI untuk diverifikasi.</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const selectAllBtn = document.getElementById('selectAllBtn');
    const clearAllBtn = document.getElementById('clearAllBtn');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const quantityInputs = document.querySelectorAll('.quantity-input');
    const selectedCountDisplay = document.getElementById('selectedCount');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('bulkBorrowForm');

    // Select All checkbox
    if(selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
                const row = checkbox.closest('tr');
                const quantityInput = row.querySelector('.quantity-input');
                if(quantityInput) quantityInput.disabled = !isChecked;
            });
            updateSelectedCount();
        });
    }

    // Select All button
    if(selectAllBtn) {
        selectAllBtn.addEventListener('click', function() {
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
                const row = checkbox.closest('tr');
                const quantityInput = row.querySelector('.quantity-input');
                if(quantityInput) quantityInput.disabled = false;
            });
            if(selectAllCheckbox) selectAllCheckbox.checked = true;
            updateSelectedCount();
        });
    }

    // Clear All button
    if(clearAllBtn) {
        clearAllBtn.addEventListener('click', function() {
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
                const row = checkbox.closest('tr');
                const quantityInput = row.querySelector('.quantity-input');
                if(quantityInput) {
                    quantityInput.disabled = true;
                    quantityInput.value = 1;
                }
            });
            if(selectAllCheckbox) selectAllCheckbox.checked = false;
            updateSelectedCount();
        });
    }

    // Individual checkbox change
    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const row = this.closest('tr');
            const quantityInput = row.querySelector('.quantity-input');
            if(quantityInput) {
                quantityInput.disabled = !this.checked;
                if (!this.checked) {
                    quantityInput.value = 1;
                }
            }

            // Update select all checkbox state
            if(selectAllCheckbox) {
                const allChecked = Array.from(itemCheckboxes).every(cb => cb.checked);
                const anyChecked = Array.from(itemCheckboxes).some(cb => cb.checked);
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = anyChecked && !allChecked;
            }

            updateSelectedCount();
        });
    });

    // Update selected count
    function updateSelectedCount() {
        if(selectedCountDisplay) {
            const count = Array.from(itemCheckboxes).filter(cb => cb.checked).length;
            selectedCountDisplay.textContent = count;
        }
        if(submitBtn) {
            submitBtn.disabled = Array.from(itemCheckboxes).filter(cb => cb.checked).length === 0;
        }
    }

    // Form validation
    if (form) {
        form.addEventListener('submit', function(e) {
            const checkedItems = Array.from(itemCheckboxes).filter(cb => cb.checked);
            
            if (checkedItems.length === 0) {
                e.preventDefault();
                alert('Pilih minimal satu barang untuk dipinjam!');
                return;
            }

            const borrowDateInput = document.getElementById('borrow_date_input');
            const returnDateInput = document.getElementById('return_date_input');

            if (!borrowDateInput || !borrowDateInput.value) {
                e.preventDefault();
                alert('Pilih tanggal mulai pinjam!');
                return;
            }

            if (!returnDateInput || !returnDateInput.value) {
                e.preventDefault();
                alert('Pilih tanggal pengembalian!');
                return;
            }

            if (returnDateInput.value <= borrowDateInput.value) {
                e.preventDefault();
                alert('Tanggal pengembalian harus setelah tanggal mulai pinjam!');
                return;
            }

            // Show confirmation
            const itemNames = checkedItems.map(cb => cb.dataset.itemName).join(', ');
            if (!confirm(`Anda akan mengajukan peminjaman untuk ${checkedItems.length} item: ${itemNames}.\nLanjutkan?`)) {
                e.preventDefault();
            }
        });
    }

    // Search filter logic
    const searchInput = document.getElementById('searchItem');
    const itemRows = document.querySelectorAll('.item-row');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();

            itemRows.forEach(row => {
                const badgeElement = row.querySelector('.fw-bold.text-dark');
                const pElement = row.querySelector('small.text-muted');
                const categoryElement = row.querySelector('td:nth-child(3) .badge');
                
                const itemName = badgeElement ? badgeElement.textContent.toLowerCase() : '';
                const itemBarcode = pElement ? pElement.textContent.toLowerCase() : '';
                const itemCategory = categoryElement ? categoryElement.textContent.toLowerCase() : '';
                
                if (itemName.includes(searchTerm) || itemBarcode.includes(searchTerm) || itemCategory.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                    // Uncheck hidden items
                    const checkbox = row.querySelector('.item-checkbox');
                    if(checkbox && checkbox.checked) {
                        checkbox.checked = false;
                        checkbox.dispatchEvent(new Event('change'));
                    }
                }
            });

            // Update selectAll checked state based on visible items
            updateSelectedCount();
        });
    }
});
</script>
@endsection
