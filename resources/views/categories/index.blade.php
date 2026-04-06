@extends('layouts.app')

@section('title', 'Kelola Kategori')

@section('content')
{{-- Page Header --}}
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h3><i class="fas fa-tags me-2 text-danger"></i>Kelola Kategori</h3>
        <p class="text-muted mb-0 small">Kelola seluruh kategori barang inventaris PMI</p>
    </div>
    <a href="{{ route('categories.create') }}" class="btn btn-danger">
        <i class="fas fa-plus me-2"></i>Tambah Kategori
    </a>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2 text-danger"></i>
                    Daftar Kategori Barang
                </h5>
                <span class="badge bg-danger rounded-pill">{{ $categories->count() }} Kategori</span>
            </div>
            <div class="card-body p-0">
                @if($categories->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr style="border-bottom: 2px solid #e60000;">
                                    <th style="font-size:0.82rem; text-transform:uppercase; letter-spacing:.5px; padding:16px 16px; color:#333; font-weight:600;">No</th>
                                    <th style="font-size:0.82rem; text-transform:uppercase; letter-spacing:.5px; padding:16px 12px; color:#333; font-weight:600;">Nama Kategori</th>
                                    <th style="font-size:0.82rem; text-transform:uppercase; letter-spacing:.5px; padding:16px 12px; color:#333; font-weight:600;">Deskripsi</th>
                                    <th style="font-size:0.82rem; text-transform:uppercase; letter-spacing:.5px; padding:16px 12px; color:#333; font-weight:600;" class="text-center">Jumlah Barang</th>
                                    <th style="font-size:0.82rem; text-transform:uppercase; letter-spacing:.5px; padding:16px 12px; color:#333; font-weight:600;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                @foreach($categories as $index => $category)
                                <tr style="transition: all 0.3s;" class="hover-shadow-row">
                                    <td class="px-4"><strong class="text-muted">{{ $index + 1 }}</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-3"
                                                style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#e60000,#9b2c9b);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.85rem;flex-shrink:0;">
                                                {{ strtoupper(substr($category->name, 0, 1)) }}
                                            </div>
                                            <strong>{{ $category->name }}</strong>
                                        </div>
                                    </td>
                                    <td class="text-muted">
                                        {{ $category->description ?? '-' }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-danger rounded-pill">{{ $category->items->count() }} barang</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('categories.edit', $category) }}"
                                               class="btn btn-sm btn-light text-warning border shadow-sm rounded-pill" title="Edit" style="width:32px; height:32px; display:flex; align-items:center; justify-content:center; transition:all 0.2s;">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('categories.destroy', $category) }}"
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus kategori {{ $category->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light text-danger border shadow-sm rounded-pill" title="Hapus" style="width:32px; height:32px; display:flex; align-items:center; justify-content:center; transition:all 0.2s;">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#e60000,#9b2c9b);display:flex;align-items:center;justify-content:center;margin:0 auto;box-shadow:0 10px 20px rgba(230,0,0,0.2);">
                                <i class="fas fa-tags fa-2x text-white"></i>
                            </div>
                        </div>
                        <h5 class="text-muted fw-bold">Belum ada kategori</h5>
                        <p class="text-muted small mb-4">Mulai dengan menambahkan kategori barang pertama</p>
                        <a href="{{ route('categories.create') }}" class="btn btn-danger rounded-pill px-4 shadow-sm" style="background:linear-gradient(135deg, #e60000, #9b2c9b); border:none;">
                            <i class="fas fa-plus me-2"></i>Tambah Kategori Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
.hover-shadow-row:hover {
    background-color: #fff !important;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    transform: translateY(-2px);
    z-index: 10;
    position: relative;
}
.btn-light:hover {
    background-color: #f8f9fa;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important;
}
</style>
@endsection