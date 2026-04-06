@extends('layouts.app')

@section('title', 'Kelola User')

@section('content')
{{-- Page Header --}}
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h3><i class="fas fa-users me-2 text-danger"></i>Kelola User</h3>
        <p class="text-muted mb-0 small">Kelola data pengguna dan hak akses</p>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2 text-danger"></i>Daftar Pengguna
                </h5>
                <span class="badge bg-danger rounded-pill">{{ $users->count() }} User</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr style="border-bottom: 2px solid #e60000;">
                                <th style="font-size:0.82rem; text-transform:uppercase; letter-spacing:.5px; padding:14px 12px; color:#333; font-weight:600;">No</th>
                                <th style="font-size:0.82rem; text-transform:uppercase; letter-spacing:.5px; padding:14px 12px; color:#333; font-weight:600;">Nama</th>
                                <th style="font-size:0.82rem; text-transform:uppercase; letter-spacing:.5px; padding:14px 12px; color:#333; font-weight:600;">Email</th>
                                <th style="font-size:0.82rem; text-transform:uppercase; letter-spacing:.5px; padding:14px 12px; color:#333; font-weight:600;">Role</th>
                                <th style="font-size:0.82rem; text-transform:uppercase; letter-spacing:.5px; padding:14px 12px; color:#333; font-weight:600;">Tgl Dibuat</th>
                                <th style="font-size:0.82rem; text-transform:uppercase; letter-spacing:.5px; padding:14px 12px; color:#333; font-weight:600;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $index => $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3" style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#e60000,#9b2c9b);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:bold;flex-shrink:0;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <strong>{{ $user->name }}</strong>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role === 'admin')
                                        <span class="badge" style="background:linear-gradient(135deg,#e60000,#9b2c9b);">Admin</span>
                                    @else
                                        <span class="badge bg-secondary">Staff</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge bg-success">Akun Anda</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
