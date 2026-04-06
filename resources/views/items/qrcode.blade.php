@extends('layouts.app')

@section('title', 'QR Code - ' . $item->name)

@section('content')
<div class="text-center">
    <h4>{{ $item->name }}</h4>
    <div class="my-4">
        {!! QrCode::size(200)->generate($item->barcode) !!}
    </div>
    <p><strong>Barcode:</strong> {{ $item->barcode }}</p>

    <a href="{{ route('items.index') }}" class="btn btn-primary mt-3">Kembali</a>
</div>
@endsection