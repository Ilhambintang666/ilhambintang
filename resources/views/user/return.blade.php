@extends('layouts.app')

@section('title', 'Pengembalian Barang')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h2>Pengembalian Barang</h2>
        <a href="{{ route('user.my-borrowings') }}" class="btn btn-secondary">Kembali ke Peminjaman Saya</a>
    </div>
</div>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h5>Detail Peminjaman</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nama Barang:</strong> {{ $loan->item->name }}</p>
                        <p><strong>Kategori:</strong> {{ $loan->item->category->name }}</p>
                        <p><strong>Lokasi:</strong> {{ $loan->item->location->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Tanggal Pinjam:</strong> {{ \Carbon\Carbon::parse($loan->created_at)->format('d/m/Y') }}</p>
                        <p><strong>Tanggal Kembali Diharapkan:</strong> {{ $loan->expected_return_date ? \Carbon\Carbon::parse($loan->expected_return_date)->format('d/m/Y') : '-' }}</p>
                        <p><strong>Status:</strong>
                            <span class="badge bg-success">Disetujui</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5>Form Pengembalian</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('user.return', $loan) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="return_date" class="form-label">Tanggal Pengembalian <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('return_date') is-invalid @enderror"
                               id="return_date" name="return_date" value="{{ old('return_date', date('Y-m-d')) }}" required>
                        @error('return_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="return_photo" class="form-label">Foto Barang yang Dikembalikan <span class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('return_photo') is-invalid @enderror"
                               id="return_photo" name="return_photo" accept="image/*" required>
                        @error('return_photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Upload foto barang dalam kondisi baik untuk verifikasi admin. Format: JPG, PNG, GIF. Maksimal 2MB.</div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan (Opsional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"
                                  placeholder="Tambahkan catatan jika ada kondisi khusus atau kerusakan">{{ old('notes') }}</textarea>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Informasi:</strong> Setelah mengajukan pengembalian, admin akan memverifikasi kondisi barang dan menyetujui pengembalian Anda.
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('user.my-borrowings') }}" class="btn btn-secondary me-md-2">Batal</a>
                        <button type="submit" class="btn btn-primary">Ajukan Pengembalian</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
