<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Lengkap - PMI Semarang</title>
    <style>
        @page { 
            margin: 110px 50px 80px 50px; 
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            color: #000;
            font-size: 11px;
        }
        
        header {
            position: fixed;
            top: -110px;
            left: -50px;
            right: -50px;
            height: 82px;
        }
        
        main {
            /* Margin handled natively by @page */
        }
        .pmi-banner-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0px;
        }
        .pmi-banner-table td {
            height: 70px;
            padding: 0;
        }
        .pmi-banner-beige {
            width: 100%;
            height: 12px;
            background-color: #f0e1d2;
            margin-bottom: 40px;
        }
        
        .header-text {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .header-text h1 {
            color: #000;
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
            text-decoration: underline;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        
        .header-text p {
            margin: 3px 0 0;
            font-size: 11px;
            color: #000;
        }
        
        .footer {
            position: fixed;
            bottom: -40px;
            left: 0px;
            right: 0px;
            font-size: 10px;
            color: #000;
            line-height: 1.4;
        }
        
        .footer-red {
            color: #cc0000;
            font-weight: bold;
        }
        
        .summary-table {
            width: 100%;
            margin-bottom: 30px;
            text-align: center;
            border-collapse: separate;
            border-spacing: 10px 0;
        }
        
        .summary-card {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 15px;
            border-radius: 8px;
            width: 33%;
        }
        
        .summary-card.keluar { border-top: 4px solid #dc3545; }
        .summary-card.masuk { border-top: 4px solid #28a745; }
        .summary-card.baru { border-top: 4px solid #17a2b8; }

        .summary-card h3 {
            margin: 0;
            font-size: 28px;
            color: #343a40;
        }
        
        .summary-card p {
            margin: 5px 0 0;
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: bold;
        }
        
        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        
        .section h2 {
            font-size: 16px;
            margin-bottom: 15px;
            padding: 8px 12px;
            background-color: #f1f3f5;
            display: inline-block;
            border-radius: 4px;
        }
        
        .section.keluar h2 { color: #dc3545; background-color: #f8d7da; border: 1px solid #f5c6cb; }
        .section.masuk h2 { color: #28a745; background-color: #d4edda; border: 1px solid #c3e6cb; }
        .section.baru h2 { color: #17a2b8; background-color: #d1ecf1; border: 1px solid #bee5eb; }
        
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        
        table.data-table th, table.data-table td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: left;
            vertical-align: top;
        }
        
        table.data-table th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
        }
        
        table.data-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        
        .empty-state {
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
            border: 1px dashed #ced4da;
            color: #6c757d;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="footer">
        <span class="footer-red">Markas Palang Merah Indonesia Kota Semarang</span> Jl. Mgr. Sugiyopranoto No. 35 Semarang 50131 - Indonesia<br>
        Telepon : (024) 3541237, Fax. (024) 3583111 - Email : kota_semarang@pmi.or.id - www.pmikotasemarang.or.id
    </div>

    <header>
        <table class="pmi-banner-table">
            <tr>
                <td style="background-color: #da251d; width: 35%;"></td>
                <td style="background-color: #e55347; width: 20%;"></td>
                <td style="background-color: #f0897e; width: 18%;"></td>
                <td style="background-color: #f8c9c4; width: 12%;"></td>
                <td style="background-color: #ffffff; width: 15%; text-align: right; vertical-align: middle; padding-right: 40px;">
                    <?php 
                        $path = public_path('images/pmi.png');
                        if(file_exists($path)) {
                            $type = pathinfo($path, PATHINFO_EXTENSION);
                            $data = file_get_contents($path);
                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                            echo '<img src="'.$base64.'" height="50" alt="PMI Logo">';
                        }
                    ?>
                </td>
            </tr>
        </table>
        <div class="pmi-banner-beige"></div>
    </header>

    <!-- Pengecualian footer dari konten statis -->
    <main>

    <div class="header-text">
        <h1>LAPORAN DATA INVENTARIS</h1>
        <p>Tanggal Cetak: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
    
    <div style="font-weight: bold; margin-bottom: 15px; text-transform: uppercase;">
        Periode: {{ \Carbon\Carbon::parse($dari)->format('d F Y') }} - {{ \Carbon\Carbon::parse($sampai)->format('d F Y') }}
    </div>
    
    <table class="summary-table">
        <tr>
            <td class="summary-card keluar">
                <h3>{{ $barangDipinjam->count() }}</h3>
                <p>1. Pre-Dipinjam</p>
            </td>
            <td class="summary-card masuk">
                <h3>{{ $barangDikembalikan->count() }}</h3>
                <p>2. Pengembalian</p>
            </td>
            <td class="summary-card baru">
                <h3>{{ $barangBaruMasuk->count() }}</h3>
                <p>3. Aset Ditambahkan</p>
            </td>
        </tr>
    </table>
    
    <div class="section keluar">
        <h2>1. Laporan Peminjaman</h2>
        
        @if($barangDipinjam->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Tanggal</th>
                    <th width="35%">Nama Barang</th>
                    <th width="10%">Jumlah</th>
                    <th width="35%">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($barangDipinjam as $index => $item)
                <tr>
                    <td align="center">{{ $index + 1 }}</td>
                    <td>{{ $item->tanggal }}</td>
                    <td>
                        <strong>{{ $item->nama_barang }}</strong><br>
                        <span style="color: #666; font-size: 9px;">SN/Barcode: {{ $item->barcode }}</span>
                    </td>
                    <td align="center">{{ $item->jumlah }}</td>
                    <td>{{ $item->keterangan }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">Tidak ada aktivitas peminjaman barang pada periode yang dipilih.</div>
        @endif
    </div>
    
    <div class="section masuk">
        <h2>2. Laporan Pengembalian</h2>
        
        @if($barangDikembalikan->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Tanggal</th>
                    <th width="35%">Nama Barang</th>
                    <th width="10%">Jumlah</th>
                    <th width="35%">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($barangDikembalikan as $index => $item)
                <tr>
                    <td align="center">{{ $index + 1 }}</td>
                    <td>{{ $item->tanggal }}</td>
                    <td>
                        <strong>{{ $item->nama_barang }}</strong><br>
                        <span style="color: #666; font-size: 9px;">SN/Barcode: {{ $item->barcode }}</span>
                    </td>
                    <td align="center">{{ $item->jumlah }}</td>
                    <td>{{ $item->keterangan }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">Tidak ada aktivitas pengembalian barang pada periode yang dipilih.</div>
        @endif
    </div>

    <div class="section baru">
        <h2>3. Laporan Barang Baru (Aset)</h2>
        
        @if($barangBaruMasuk->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Tanggal Beli</th>
                    <th width="35%">Nama Barang</th>
                    <th width="10%">Jumlah</th>
                    <th width="35%">Detail & Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach($barangBaruMasuk as $index => $item)
                <tr>
                    <td align="center">{{ $index + 1 }}</td>
                    <td>{{ $item->tanggal }}</td>
                    <td>
                        <strong>{{ $item->nama_barang }}</strong><br>
                        <span style="color: #666; font-size: 9px;">SN/Barcode: {{ $item->barcode }}</span>
                    </td>
                    <td align="center">{{ $item->jumlah }}</td>
                    <td>{!! nl2br(e($item->keterangan)) !!}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">Tidak ada penambahan aset inventaris baru pada periode yang dipilih.</div>
        @endif
    </div>
    
    </main>
</body>
</html>
