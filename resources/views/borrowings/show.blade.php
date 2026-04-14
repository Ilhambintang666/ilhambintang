@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
{{-- Page Header --}}
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h3><i class="fas fa-hand-holding me-2 text-danger"></i>Detail Peminjaman</h3>
        <p class="text-muted mb-0 small">Informasi lengkap data peminjaman barang</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('borrowings.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-10">

        {{-- Info Cards Row --}}
        <div class="row mb-4">
            {{-- Info Peminjam --}}
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="mb-0">
                            <i class="fas fa-user me-2 text-danger"></i>Informasi Peminjam
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div style="width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,#e60000,#9b2c9b);display:flex;align-items:center;justify-content:center;flex-shrink:0;" class="me-3">
                                <span class="text-white fw-bold">{{ strtoupper(substr($borrowing->borrower_name ?? 'A', 0, 1)) }}</span>
                            </div>
                            <div>
                                <div class="fw-bold">{{ $borrowing->borrower_name }}</div>
                                <small class="text-muted">User Sistem</small>
                            </div>
                        </div>
                        <table class="table table-borderless table-sm mb-0">
                            <tr>
                                <td class="text-muted fw-semibold" width="40%">Status Peminjaman</td>
                                <td>
                                    @switch($borrowing->status)
                                        @case('dipinjam')
                                            @if($borrowing->expected_return_date->isPast())
                                                <span class="badge bg-danger">Terlambat</span>
                                            @else
                                                <span class="badge bg-warning">Dipinjam</span>
                                            @endif
                                            @break
                                        @case('menunggu')
                                            <span class="badge bg-secondary">Menunggu</span>
                                            @break
                                        @case('disetujui')
                                            <span class="badge bg-warning">Disetujui</span>
                                            @break
                                        @case('dikembalikan')
                                            <span class="badge bg-success">Dikembalikan</span>
                                            @break
                                        @case('terlambat')
                                            <span class="badge bg-danger">Terlambat</span>
                                            @break
                                        @case('ditolak')
                                            <span class="badge bg-danger">Ditolak</span>
                                            @break
                                    @endswitch
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Info Barang --}}
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="mb-0">
                            <i class="fas fa-box me-2 text-danger"></i>Informasi Barang & Bukti
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div style="width:48px;height:48px;border-radius:10px;background:linear-gradient(135deg,#e60000,#9b2c9b);display:flex;align-items:center;justify-content:center;flex-shrink:0;" class="me-3">
                                <i class="fas fa-box text-white"></i>
                            </div>
                            <div>
                                <div class="fw-bold">{{ $borrowing->item->name }}</div>
                                <code class="small">{{ $borrowing->item->barcode }}</code>
                            </div>
                        </div>
                        <table class="table table-borderless table-sm mb-3">
                            <tr>
                                <td class="text-muted fw-semibold" width="40%">Kategori</td>
                                <td><span class="badge" style="background:linear-gradient(135deg,#e60000,#9b2c9b);">{{ $borrowing->item->category->name }}</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold">Lokasi</td>
                                <td><span class="badge bg-secondary">{{ $borrowing->item->location->name }}</span></td>
                            </tr>
                        </table>
                        
                        @if(!empty($return_photo))
                            <div class="mt-3 pt-3 border-top">
                                <h6 class="small fw-bold text-muted mb-2"><i class="fas fa-image me-1"></i>Bukti Pengembalian:</h6>
                                <a href="{{ asset('storage/' . $return_photo) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $return_photo) }}" alt="Bukti Pengembalian" class="img-fluid rounded border shadow-sm" style="max-height: 120px; object-fit: cover;">
                                </a>
                            </div>
                        @else
                            @if(in_array($borrowing->status, ['dikembalikan', 'terlambat']))
                                <div class="mt-3 pt-3 border-top">
                                    <h6 class="small fw-bold text-muted mb-2"><i class="fas fa-image me-1"></i>Bukti Pengembalian:</h6>
                                    <p class="small text-muted fst-italic">Foto bukti pengembalian tidak tersedia atau belum diunggah.</p>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Timeline --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0">
                    <i class="fas fa-calendar-alt me-2 text-danger"></i>Timeline Peminjaman
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="text-center p-3 rounded-3" style="background:linear-gradient(135deg,#e60000,#9b2c9b);">
                            <i class="fas fa-calendar-plus fa-2x text-white mb-2"></i>
                            <div class="text-white fw-semibold small">Tanggal Pinjam</div>
                            <div class="text-white fw-bold h6 mb-1">{{ $borrowing->borrow_date->format('d M Y') }}</div>
                            <small class="text-white-50">{{ $borrowing->borrow_date->diffForHumans() }}</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        @php
                            $isLate = $borrowing->expected_return_date->isPast() && $borrowing->status === 'dipinjam';
                            $bg = $isLate ? 'background:#dc3545;' : 'background:#ffc107;';
                        @endphp
                        <div class="text-center p-3 rounded-3" style="{{ $bg }}">
                            <i class="fas fa-calendar-check fa-2x text-white mb-2"></i>
                            <div class="text-white fw-semibold small">Target Kembali</div>
                            <div class="text-white fw-bold h6 mb-1">{{ $borrowing->expected_return_date->format('d M Y') }}</div>
                            <small class="text-white-50">
                                @if($isLate)
                                    Terlambat {{ $borrowing->expected_return_date->diffForHumans() }}
                                @else
                                    {{ $borrowing->expected_return_date->diffForHumans() }}
                                @endif
                            </small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        @if($borrowing->return_date)
                            <div class="text-center p-3 rounded-3" style="background:#198754;">
                                <i class="fas fa-calendar-check fa-2x text-white mb-2"></i>
                                <div class="text-white fw-semibold small">Tanggal Kembali</div>
                                <div class="text-white fw-bold h6 mb-1">{{ $borrowing->return_date->format('d M Y') }}</div>
                                <small class="text-white-50">{{ $borrowing->return_date->diffForHumans() }}</small>
                            </div>
                        @else
                            <div class="text-center p-3 rounded-3 border" style="background:#f8f9fa;">
                                <i class="fas fa-calendar-minus fa-2x text-muted mb-2"></i>
                                <div class="text-muted fw-semibold small">Tanggal Kembali</div>
                                <div class="text-muted fw-bold h6 mb-1">-</div>
                                <small class="text-muted">Belum dikembalikan</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Durasi & Catatan --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <h6 class="mb-2"><i class="fas fa-clock me-2 text-danger"></i>Durasi Peminjaman</h6>
                        <div class="small text-muted">
                            <div><strong>Target:</strong> {{ $borrowing->borrow_date->diffInDays($borrowing->expected_return_date) }} hari</div>
                            @if($borrowing->return_date)
                                <div><strong>Aktual:</strong> {{ $borrowing->borrow_date->diffInDays($borrowing->return_date) }} hari</div>
                            @else
                                <div><strong>Sudah berjalan:</strong> {{ $borrowing->borrow_date->diffInDays(now()) }} hari</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @if($borrowing->notes)
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <h6 class="mb-2"><i class="fas fa-sticky-note me-2 text-danger"></i>Catatan</h6>
                        <p class="mb-0 small text-muted">{{ $borrowing->notes }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Action Buttons --}}
        <div class="d-flex justify-content-between">
            <a href="{{ route('borrowings.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali ke Daftar
            </a>
            <div class="d-flex gap-2">
                @if($borrowing->status === 'dipinjam')
                    <a href="{{ route('borrowings.edit', $borrowing) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection