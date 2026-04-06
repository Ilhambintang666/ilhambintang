@extends('layouts.app')

@section('title', 'Tambah Lokasi')

@section('content')
{{-- Page Header --}}
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h3><i class="fas fa-plus me-2 text-danger"></i>Tambah Lokasi</h3>
        <p class="text-muted mb-0 small">Tambahkan lokasi penyimpanan baru</p>
    </div>
    <a href="{{ route('locations.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-header border-0 py-4" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
                <h5 class="mb-0 fw-bold text-dark">
                    <div style="width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg,#e60000,#9b2c9b);display:inline-flex;align-items:center;justify-content:center;color:#fff;margin-right:10px;box-shadow:0 4px 6px rgba(230,0,0,0.2);">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    Form Lokasi Baru
                </h5>
            </div>
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('locations.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="form-label fw-bold text-muted small text-uppercase tracking-wide">
                            Nama Lokasi <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control form-control-lg premium-input @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="Contoh: Gudang Utama, Lantai 2, Ruang IT"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label for="description" class="form-label fw-bold text-muted small text-uppercase tracking-wide">Deskripsi</label>
                        <textarea class="form-control premium-input @error('description') is-invalid @enderror"
                                  id="description"
                                  name="description"
                                  rows="4"
                                  placeholder="Deskripsi lokasi penyimpanan (opsional)">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text mt-2"><i class="fas fa-info-circle me-1"></i> Berikan deskripsi detail tentang lokasi ini.</div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 pt-3 border-top">
                        <a href="{{ route('locations.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm fw-semibold hover-lift">
                            <i class="fas fa-times me-1 text-muted"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-danger rounded-pill px-5 shadow-sm fw-semibold hover-lift" style="background:linear-gradient(135deg, #e60000, #9b2c9b); border:none;">
                            <i class="fas fa-save me-1"></i> Simpan Lokasi
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
.tracking-wide {
    letter-spacing: 0.05em;
}
.hover-lift {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.hover-lift:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.1) !important;
}
</style>
@endsection