@extends('layouts.app')

@section('title', 'Pengembalian Saya')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pengembalian Saya</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($returnRequests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Tanggal Pengembalian</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($returnRequests as $request)
                                        <tr>
                                            <td>{{ $request->item->name }}</td>
                                            <td>{{ $request->updated_at->format('d/m/Y H:i') }}</td>
                                            <td>{{ $request->return_date ? $request->return_date->format('d/m/Y') : '-' }}</td>
                                            <td>
                                                @if($request->status == 'return_pending')
                                                    <span class="badge badge-warning">Menunggu Persetujuan</span>
                                                @elseif($request->status == 'returned')
                                                    <span class="badge badge-success">Dikembalikan</span>
                                                @elseif($request->status == 'rejected')
                                                    <span class="badge badge-danger">Ditolak</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($request->status == 'return_pending')
                                                    <span class="text-muted">Menunggu admin</span>
                                                @elseif($request->status == 'returned')
                                                    <span class="text-success">Berhasil dikembalikan</span>
                                                @elseif($request->status == 'rejected')
                                                    <a href="{{ route('user.return.form', $request->id) }}" class="btn btn-sm btn-primary">Ajukan Ulang</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">Belum ada pengajuan pengembalian</h4>
                            <p class="text-muted">Pengajuan pengembalian Anda akan muncul di sini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
