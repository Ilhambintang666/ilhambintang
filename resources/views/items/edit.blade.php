@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')
{{-- Page Header --}}
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h3><i class="fas fa-edit me-2 text-danger"></i>Edit Barang</h3>
        <p class="text-muted mb-0 small">Perbarui informasi barang: <strong>{{ $item->name }}</strong></p>
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
                        <i class="fas fa-edit"></i>
                    </div>
                    Form Edit Barang
                </h5>
            </div>
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('items.update', $item) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        {{-- Kolom Kiri --}}
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="name" class="form-label fw-bold text-muted small text-uppercase tracking-wide">
                                    Nama Barang <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       class="form-control form-control-lg premium-input @error('name') is-invalid @enderror"
                                       id="name" name="name"
                                       value="{{ old('name', $item->name) }}"
                                       placeholder="Contoh: Laptop Asus X441"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="barcode" class="form-label fw-bold text-muted small text-uppercase tracking-wide">
                                    Barcode <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       class="form-control form-control-lg premium-input @error('barcode') is-invalid @enderror"
                                       id="barcode" name="barcode"
                                       value="{{ old('barcode', $item->barcode) }}"
                                       placeholder="Contoh: PMI001"
                                       required>
                                @error('barcode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text mt-2"><i class="fas fa-info-circle me-1"></i> Barcode harus unik untuk setiap barang</div>
                            </div>

                            <div class="mb-4">
                                <label for="category_id" class="form-label fw-bold text-muted small text-uppercase tracking-wide">
                                    Kategori <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-select-lg premium-input @error('category_id') is-invalid @enderror"
                                        id="category_id" name="category_id" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                                {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
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
                                        id="location_id" name="location_id" required>
                                    <option value="">Pilih Lokasi</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}"
                                                {{ old('location_id', $item->location_id) == $location->id ? 'selected' : '' }}>
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
                                        id="condition" name="condition" required>
                                    <option value="">Pilih Kondisi</option>
                                    <option value="baik" {{ old('condition', $item->condition) == 'baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="rusak" {{ old('condition', $item->condition) == 'rusak' ? 'selected' : '' }}>Rusak</option>
                                    <option value="dalam_perbaikan" {{ old('condition', $item->condition) == 'dalam_perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
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
                                        id="status" name="status" required>
                                    <option value="">Pilih Status</option>
                                    <option value="tersedia" {{ old('status', $item->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="dipinjam" {{ old('status', $item->status) == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                    <option value="maintenance" {{ old('status', $item->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
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
                                       id="quantity" name="quantity"
                                       value="{{ old('quantity', $item->quantity) }}"
                                       min="1" required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="purchase_date" class="form-label fw-bold text-muted small text-uppercase tracking-wide">Tanggal Pembelian</label>
                                <input type="date"
                                       class="form-control form-control-lg premium-input @error('purchase_date') is-invalid @enderror"
                                       id="purchase_date" name="purchase_date"
                                       value="{{ old('purchase_date', $item->purchase_date?->format('Y-m-d')) }}">
                                @error('purchase_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="price" class="form-label fw-bold text-muted small text-uppercase tracking-wide">Harga (Rp)</label>
                                <input type="number"
                                       class="form-control form-control-lg premium-input @error('price') is-invalid @enderror"
                                       id="price" name="price"
                                       value="{{ old('price', $item->price) }}"
                                       min="0" step="0.01" placeholder="0.00">
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
                                    <label class="d-block" for="loanable_yes">
                                        <div class="p-3 border rounded-3 {{ old('is_loanable', $item->is_loanable ? '1' : '0') == '1' ? 'border-success bg-success bg-opacity-5' : 'border-light' }}"
                                             id="loanable_yes_box" style="cursor:pointer; transition: all 0.2s;">
                                            <div class="d-flex align-items-center gap-3">
                                                <input type="radio" name="is_loanable" id="loanable_yes" value="1"
                                                       class="form-check-input mt-0"
                                                       {{ old('is_loanable', $item->is_loanable ? '1' : '0') == '1' ? 'checked' : '' }}
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
                                    <label class="d-block" for="loanable_no">
                                        <div class="p-3 border rounded-3 {{ old('is_loanable', $item->is_loanable ? '1' : '0') == '0' ? 'border-danger bg-danger bg-opacity-5' : 'border-light' }}"
                                             id="loanable_no_box" style="cursor:pointer; transition: all 0.2s;">
                                            <div class="d-flex align-items-center gap-3">
                                                <input type="radio" name="is_loanable" id="loanable_no" value="0"
                                                       class="form-check-input mt-0"
                                                       {{ old('is_loanable', $item->is_loanable ? '1' : '0') == '0' ? 'checked' : '' }}
                                                       onchange="updateLoanableStyle()">
                                                <div>
                                                    <div class="fw-bold text-dark"><i class="fas fa-lock me-1 text-danger"></i> Barang Paten</div>
                                                    <div class="text-muted small">Inventaris tetap, tidak dapat dipinjam</div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-5">
                        <label for="description" class="form-label fw-bold text-muted small text-uppercase tracking-wide">Deskripsi</label>
                        <textarea class="form-control premium-input @error('description') is-invalid @enderror"
                                  id="description" name="description"
                                  rows="3"
                                  placeholder="Deskripsi detail barang (opsional)">{{ old('description', $item->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info border-0 shadow-sm rounded-3 mb-5"
                         style="background:rgba(13,202,240,0.05); border-left: 4px solid #0dcaf0 !important;">
                        <i class="fas fa-info-circle me-2 text-info"></i>
                        Barang ini memiliki <strong class="fs-5">{{ $item->borrowings->count() }}</strong> riwayat peminjaman.
                    </div>

                    <div class="d-flex justify-content-end gap-3 pt-3 border-top">
                        <a href="{{ route('items.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm fw-semibold hover-lift">
                            <i class="fas fa-times me-1 text-muted"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-danger rounded-pill px-5 shadow-sm fw-semibold hover-lift" style="background:linear-gradient(135deg, #e60000, #9b2c9b); border:none;">
                            <i class="fas fa-save me-1"></i> Update Barang
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