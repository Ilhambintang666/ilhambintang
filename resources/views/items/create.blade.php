@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
{{-- Page Header --}}
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h3><i class="fas fa-plus me-2 text-danger"></i>Tambah Barang</h3>
        <p class="text-muted mb-0 small">Tambahkan barang inventaris baru ke sistem</p>
    </div>
    <a href="{{ route('items.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-header border-0 py-4" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
                <h5 class="mb-0 fw-bold text-dark">
                    <div style="width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg,#e60000,#9b2c9b);display:inline-flex;align-items:center;justify-content:center;color:#fff;margin-right:10px;box-shadow:0 4px 6px rgba(230,0,0,0.2);">
                        <i class="fas fa-box"></i>
                    </div>
                    Form Barang Baru
                </h5>
            </div>
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('items.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        {{-- Kolom Kiri --}}
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="name" class="form-label fw-bold text-muted small text-uppercase tracking-wide">
                                    Nama Barang <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       class="form-control form-control-lg premium-input @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       value="{{ old('name') }}"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="prefix" class="form-label fw-bold text-muted small text-uppercase tracking-wide">
                                    Kode Barang
                                </label>
                                <input type="text"
                                       class="form-control form-control-lg premium-input @error('prefix') is-invalid @enderror"
                                       id="prefix"
                                       name="prefix"
                                       value="{{ old('prefix') }}"
                                       placeholder="DNR, MED, ER, OFF, FUR"
                                       maxlength="10">
                                @error('prefix')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text mt-2"><i class="fas fa-info-circle me-1"></i> MED: Medikal, DNR: Donor Darah, ER: Emergency Response, OFF: Office, FUR: Furniture</div>
                            </div>

                            <div class="mb-4">
                                <label for="category_id" class="form-label fw-bold text-muted small text-uppercase tracking-wide">
                                    Kategori <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-select-lg premium-input @error('category_id') is-invalid @enderror"
                                        id="category_id"
                                        name="category_id"
                                        required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="location_id" class="form-label fw-bold text-muted small text-uppercase tracking-wide">
                                    Lokasi <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-select-lg premium-input @error('location_id') is-invalid @enderror"
                                        id="location_id"
                                        name="location_id"
                                        required>
                                    <option value="">Pilih Lokasi</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                            {{ $location->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('location_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Kolom Kanan --}}
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="condition" class="form-label fw-bold text-muted small text-uppercase tracking-wide">
                                    Kondisi <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-select-lg premium-input @error('condition') is-invalid @enderror"
                                        id="condition"
                                        name="condition"
                                        required>
                                    <option value="">Pilih Kondisi</option>
                                    <option value="baik" {{ old('condition') == 'baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="rusak" {{ old('condition') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                                    <option value="dalam_perbaikan" {{ old('condition') == 'dalam_perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                                </select>
                                @error('condition')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="status" class="form-label fw-bold text-muted small text-uppercase tracking-wide">
                                    Status <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-select-lg premium-input @error('status') is-invalid @enderror"
                                        id="status"
                                        name="status"
                                        required>
                                    <option value="">Pilih Status</option>
                                    <option value="tersedia" {{ old('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="dipinjam" {{ old('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                    <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="quantity" class="form-label fw-bold text-muted small text-uppercase tracking-wide">
                                    Jumlah <span class="text-danger">*</span>
                                </label>
                                <input type="number"
                                       class="form-control form-control-lg premium-input @error('quantity') is-invalid @enderror"
                                       id="quantity"
                                       name="quantity"
                                       value="{{ old('quantity', 1) }}"
                                       min="1"
                                       required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="purchase_date" class="form-label fw-bold text-muted small text-uppercase tracking-wide">Tanggal Pembelian</label>
                                <input type="date"
                                       class="form-control form-control-lg premium-input @error('purchase_date') is-invalid @enderror"
                                       id="purchase_date"
                                       name="purchase_date"
                                       value="{{ old('purchase_date') }}">
                                @error('purchase_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="price" class="form-label fw-bold text-muted small text-uppercase tracking-wide">Harga (Rp)</label>
                                <input type="number"
                                       class="form-control form-control-lg premium-input @error('price') is-invalid @enderror"
                                       id="price"
                                       name="price"
                                       value="{{ old('price') }}"
                                       min="0"
                                       step="0.01"
                                       placeholder="0.00">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

    {{-- Status Peminjaman --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted small text-uppercase tracking-wide">
                                Status Peminjaman <span class="text-danger">*</span>
                            </label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="d-block cursor-pointer" for="loanable_yes">
                                        <div class="p-3 border rounded-3 loanable-option {{ old('is_loanable', '1') == '1' ? 'border-success bg-success bg-opacity-5' : 'border-light' }}"
                                             id="loanable_yes_box" style="cursor:pointer; transition: all 0.2s;">
                                            <div class="d-flex align-items-center gap-3">
                                                <input type="radio" name="is_loanable" id="loanable_yes" value="1"
                                                       class="form-check-input mt-0"
                                                       {{ old('is_loanable', '1') == '1' ? 'checked' : '' }}
                                                       onchange="updateLoanableStyle()">
                                                <div>
                                                    <div class="fw-bold text-dark"><i class="fas fa-hand-holding me-1 text-success"></i> Barang Pinjaman</div>
                                                    <div class="text-muted small">Dapat dipinjam oleh pengguna</div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <label class="d-block cursor-pointer" for="loanable_no">
                                        <div class="p-3 border rounded-3 loanable-option {{ old('is_loanable', '1') == '0' ? 'border-danger bg-danger bg-opacity-5' : 'border-light' }}"
                                             id="loanable_no_box" style="cursor:pointer; transition: all 0.2s;">
                                            <div class="d-flex align-items-center gap-3">
                                                <input type="radio" name="is_loanable" id="loanable_no" value="0"
                                                       class="form-check-input mt-0"
                                                       {{ old('is_loanable', '1') == '0' ? 'checked' : '' }}
                                                       onchange="updateLoanableStyle()">
                                                <div>
                                                    <div class="fw-bold text-dark"><i class="fas fa-lock me-1 text-danger"></i> Barang Tetap</div>
                                                    <div class="text-muted small">Inventaris tetap, tidak dapat dipinjam</div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Deskripsi Full Width --}}
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-5">
                                <label for="description" class="form-label fw-bold text-muted small text-uppercase tracking-wide">Deskripsi</label>
                                <textarea class="form-control premium-input @error('description') is-invalid @enderror"
                                          id="description"
                                          name="description"
                                          rows="3"
                                          placeholder="Deskripsi detail barang (opsional)">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 pt-3 border-top">
                        <a href="{{ route('items.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm fw-semibold hover-lift">
                            <i class="fas fa-times me-1 text-muted"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-danger rounded-pill px-5 shadow-sm fw-semibold hover-lift" style="background:linear-gradient(135deg, #e60000, #9b2c9b); border:none;">
                            <i class="fas fa-save me-1"></i> Simpan Barang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
.premium-input {
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 10px;
    padding: 12px 15px;
    transition: all 0.3s ease;
}
.premium-input:focus {
    background-color: #fff;
    border-color: #9b2c9b;
    box-shadow: 0 0 0 4px rgba(155, 44, 155, 0.1);
}
.tracking-wide { letter-spacing: 0.05em; }
.hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
.hover-lift:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(0,0,0,0.1) !important; }
</style>
<script>
function updateLoanableStyle() {
    const yesChecked = document.getElementById('loanable_yes').checked;
    const yesBox = document.getElementById('loanable_yes_box');
    const noBox  = document.getElementById('loanable_no_box');
    if (yesChecked) {
        yesBox.className = 'p-3 border rounded-3 border-success bg-success bg-opacity-5';
        noBox.className  = 'p-3 border rounded-3 border-light';
    } else {
        noBox.className  = 'p-3 border rounded-3 border-danger bg-danger bg-opacity-5';
        yesBox.className = 'p-3 border rounded-3 border-light';
    }
}
</script>
@endsection