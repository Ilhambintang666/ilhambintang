@extends('layouts.app')

@section('title', 'Edit Peminjaman')

@section('content')
{{-- Page Header --}}
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h3><i class="fas fa-edit me-2 text-danger"></i>Edit Peminjaman</h3>
        <p class="text-muted mb-0 small">Perbarui data peminjaman barang</p>
    </div>
    <a href="{{ route('borrowings.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0">
                    <i class="fas fa-hand-holding me-2 text-danger"></i>
                    Form Edit Peminjaman
                </h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('borrowings.update', $borrowing) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="user_id" class="form-label fw-semibold">
                            Peminjam <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('user_id') is-invalid @enderror"
                                id="user_id" name="user_id" required>
                            <option value="">Pilih Peminjam</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}"
                                        {{ old('user_id', $borrowing->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} - {{ $user->email }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="item_id" class="form-label fw-semibold">
                            Barang <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('item_id') is-invalid @enderror"
                                id="item_id" name="item_id" required>
                            <option value="">Pilih Barang</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}"
                                        {{ old('item_id', $borrowing->item_id) == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }} - {{ $item->category->name }} ({{ $item->location->name }})
                                </option>
                            @endforeach
                        </select>
                        @error('item_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="borrow_date" class="form-label fw-semibold">
                                Tanggal Pinjam <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                   class="form-control @error('borrow_date') is-invalid @enderror"
                                   id="borrow_date" name="borrow_date"
                                   value="{{ old('borrow_date', $borrowing->borrow_date) }}" required>
                            @error('borrow_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="expected_return_date" class="form-label fw-semibold">
                                Tanggal Kembali <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                   class="form-control @error('expected_return_date') is-invalid @enderror"
                                   id="expected_return_date" name="expected_return_date"
                                   value="{{ old('expected_return_date', $borrowing->expected_return_date) }}" required>
                            @error('expected_return_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="notes" class="form-label fw-semibold">Catatan</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                  id="notes" name="notes" rows="3"
                                  placeholder="Catatan khusus (opsional)">{{ old('notes', $borrowing->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('borrowings.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-save me-1"></i>Update Peminjaman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
