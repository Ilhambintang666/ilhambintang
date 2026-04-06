@extends('layouts.app')

@section('title', 'Pinjam Barang')

@section('content')
{{-- Page Header --}}
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h3><i class="fas fa-hand-holding me-2 text-danger"></i>Pinjam Barang</h3>
        <p class="text-muted mb-0 small">Catat peminjaman barang inventaris baru</p>
    </div>
    <a href="{{ route('borrowings.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-10">
        @if($items->count() == 0)
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#e60000,#9b2c9b);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                        <i class="fas fa-exclamation-triangle fa-2x text-white"></i>
                    </div>
                    <h5 class="text-muted fw-normal">Tidak ada barang tersedia</h5>
                    <p class="text-muted small mb-4">
                        Pastikan ada barang dengan status <strong>"tersedia"</strong> dan kondisi <strong>"baik"</strong>.
                    </p>
                    <a href="{{ route('items.create') }}" class="btn btn-danger">
                        <i class="fas fa-plus me-2"></i>Tambah Barang Baru
                    </a>
                </div>
            </div>
        @else
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-hand-holding me-2 text-danger"></i>Form Peminjaman Baru
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('borrowings.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            {{-- Kolom Kiri --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="borrower_name" class="form-label fw-semibold">
                                        Nama Peminjam <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control @error('borrower_name') is-invalid @enderror"
                                           id="borrower_name"
                                           name="borrower_name"
                                           value="{{ old('borrower_name') }}"
                                           placeholder="Masukkan nama peminjam"
                                           required>
                                    @error('borrower_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="item_id" class="form-label fw-semibold">
                                        Barang <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('item_id') is-invalid @enderror"
                                            id="item_id"
                                            name="item_id"
                                            required>
                                        <option value="">Pilih Barang</option>
                                        @foreach($items as $item)
                                            <option value="{{ $item->id }}"
                                                    {{ old('item_id') == $item->id ? 'selected' : '' }}
                                                    data-category="{{ $item->category->name }}"
                                                    data-location="{{ $item->location->name }}"
                                                    data-barcode="{{ $item->barcode }}">
                                                {{ $item->name }} - {{ $item->barcode }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('item_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    {{-- Item Info Preview --}}
                                    <div id="item-info" class="mt-2" style="display: none;">
                                        <div class="p-3 rounded-3 border-start border-3 border-danger"
                                             style="background:rgba(230,0,0,0.04);">
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <small class="text-muted d-block">Kategori</small>
                                                    <strong id="item-category" class="small"></strong>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted d-block">Lokasi</small>
                                                    <strong id="item-location" class="small"></strong>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted d-block">Barcode</small>
                                                    <code id="item-barcode" class="small"></code>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted d-block">Status</small>
                                                    <span class="badge bg-success" style="font-size:.75rem;">Tersedia</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Kolom Kanan --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="borrow_date" class="form-label fw-semibold">
                                        Tanggal Pinjam <span class="text-danger">*</span>
                                    </label>
                                    <input type="date"
                                           class="form-control @error('borrow_date') is-invalid @enderror"
                                           id="borrow_date"
                                           name="borrow_date"
                                           value="{{ old('borrow_date', date('Y-m-d')) }}"
                                           required>
                                    @error('borrow_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="expected_return_date" class="form-label fw-semibold">
                                        Tanggal Kembali <span class="text-danger">*</span>
                                    </label>
                                    <input type="date"
                                           class="form-control @error('expected_return_date') is-invalid @enderror"
                                           id="expected_return_date"
                                           name="expected_return_date"
                                           value="{{ old('expected_return_date') }}"
                                           required>
                                    @error('expected_return_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Tanggal yang diharapkan untuk pengembalian barang</div>
                                </div>
                            </div>
                        </div>

                        {{-- Catatan --}}
                        <div class="mb-4">
                            <label for="notes" class="form-label fw-semibold">Catatan</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                      id="notes"
                                      name="notes"
                                      rows="3"
                                      placeholder="Catatan khusus untuk peminjaman ini (opsional)">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Info Box --}}
                        <div class="p-3 rounded-3 mb-4 border-start border-3 border-warning"
                             style="background:rgba(255,193,7,0.08);">
                            <h6 class="text-warning mb-2">
                                <i class="fas fa-info-circle me-2"></i>Perhatian
                            </h6>
                            <ul class="mb-0 small text-muted">
                                <li>Pastikan barang dalam kondisi baik saat dipinjam</li>
                                <li>Peminjam bertanggung jawab atas kerusakan atau kehilangan</li>
                                <li>Harap mengembalikan barang sesuai tanggal yang ditentukan</li>
                                <li>Status barang akan otomatis berubah menjadi "Dipinjam"</li>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('borrowings.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-save me-1"></i>Catat Peminjaman
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const itemSelect = document.getElementById('item_id');
    const itemInfo = document.getElementById('item-info');
    const borrowDate = document.getElementById('borrow_date');
    const returnDate = document.getElementById('expected_return_date');

    itemSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (this.value) {
            document.getElementById('item-category').textContent = selectedOption.dataset.category;
            document.getElementById('item-location').textContent = selectedOption.dataset.location;
            document.getElementById('item-barcode').textContent = selectedOption.dataset.barcode;
            itemInfo.style.display = 'block';
        } else {
            itemInfo.style.display = 'none';
        }
    });

    borrowDate.addEventListener('change', function() {
        if (this.value && !returnDate.value) {
            const d = new Date(this.value);
            d.setDate(d.getDate() + 7);
            returnDate.value = d.toISOString().split('T')[0];
        }
    });

    returnDate.addEventListener('change', function() {
        if (borrowDate.value && this.value) {
            if (new Date(this.value) <= new Date(borrowDate.value)) {
                alert('Tanggal kembali harus setelah tanggal pinjam!');
                this.value = '';
            }
        }
    });
});
</script>
@endsection