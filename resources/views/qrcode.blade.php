<!DOCTYPE html>
<html>
<head>
    <title>QR Code - {{ $item->Nama_Barang }}</title>
</head>
<body style="text-align:center; font-family: Arial;">
    <h2>{{ $item->Nama_Barang }}</h2>
    <p>{{ $item->Barcode }}</p>

    {!! QrCode::size(200)->generate($item->Barcode) !!}

    <br><br>
    <button onclick="window.print()">Cetak</button>
</body>
</html>
