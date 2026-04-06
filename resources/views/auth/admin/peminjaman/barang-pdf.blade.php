<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman - PMI Semarang</title>
    <style>
        @page { margin: 30px; }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            color: #333;
            font-size: 13px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e60000;
        }
        
        .header h1 {
            color: #e60000;
            margin: 0;
            font-size: 22px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .header p {
            margin: 5px 0 0;
            color: #666;
            font-size: 14px;
        }
        
        .period {
            text-align: left;
            margin-bottom: 20px;
            padding: 10px 15px;
            background-color: #f8f9fa;
            border-left: 4px solid #e60000;
            font-weight: bold;
            color: #495057;
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
        }
        
        .summary-card.keluar { border-top: 4px solid #dc3545; }
        .summary-card.masuk { border-top: 4px solid #28a745; }
        .summary-card.sisa { border-top: 4px solid #ffc107; }

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
        
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        
        table.data-table th, table.data-table td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: left;
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
        
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #dee2e6;
            text-align: right;
            font-size: 11px;
            color: #adb5bd;
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
    <div class="header">
        <h1>Laporan Sirkulasi Inventaris</h1>
        <p>Palang Merah Indonesia - Cabang Kota Semarang</p>
    </div>
    
    <div class="period">
        Periode Laporan: {{ \Carbon\Carbon::parse($dari)->format('d F Y') }} - {{ \Carbon\Carbon::parse($sampai)->format('d F Y') }}
    </div>
    
    <table class="summary-table">
        <tr>
            <td class="summary-card keluar">
                <h3>{{ $barangKeluar->count() }}</h3>
                <p>Total Peminjaman</p>
            </td>
            <td class="summary-card masuk">
                <h3>{{ $barangMasuk->count() }}</h3>
                <p>Total Pengembalian</p>
            </td>
            <td class="summary-card sisa">
                <h3>{{ $barangKeluar->count() - $barangMasuk->count() }}</h3>
                <p>Status Menggantung</p>
            </td>
        </tr>
    </table>
    
    <div class="section keluar">
        <h2>Histori Peminjaman (Barang Keluar)</h2>
        
        @if($barangKeluar->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th width="8%">No</th>
                    <th width="17%">Tanggal</th>
                    <th width="35%">Nama Barang</th>
                    <th width="10%">Jumlah</th>
                    <th width="30%">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($barangKeluar as $index => $item)
                <tr>
                    <td align="center">{{ $index + 1 }}</td>
                    <td>{{ $item->tanggal }}</td>
                    <td><strong>{{ $item->nama_barang }}</strong></td>
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
        <h2>Histori Pengembalian (Barang Masuk)</h2>
        
        @if($barangMasuk->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th width="8%">No</th>
                    <th width="17%">Tanggal</th>
                    <th width="35%">Nama Barang</th>
                    <th width="10%">Jumlah</th>
                    <th width="30%">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($barangMasuk as $index => $item)
                <tr>
                    <td align="center">{{ $index + 1 }}</td>
                    <td>{{ $item->tanggal }}</td>
                    <td><strong>{{ $item->nama_barang }}</strong></td>
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
    
    <div class="footer">
        Dokumen ini dibuat otomatis oleh Sistem Inventaris PMI Kota Semarang pada {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>
