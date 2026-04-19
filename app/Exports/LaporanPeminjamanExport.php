<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanPeminjamanExport implements FromCollection, WithHeadings
{
    protected $barangDipinjam;
    protected $barangDikembalikan;
    protected $barangBaruMasuk;

    public function __construct($barangDipinjam, $barangDikembalikan, $barangBaruMasuk)
    {
        $this->barangDipinjam = $barangDipinjam;
        $this->barangDikembalikan = $barangDikembalikan;
        $this->barangBaruMasuk = $barangBaruMasuk;
    }

    public function collection()
    {
        $data = collect();
        
        // Add barang dipinjam
        foreach ($this->barangDipinjam as $item) {
            $data->push([
                'Kategori Laporan' => '1. PRE-DIPINJAM',
                'Tanggal'          => $item->tanggal,
                'Nama Barang'      => $item->nama_barang,
                'Barcode / Seri'   => $item->barcode ?? '-',
                'Jumlah'           => $item->jumlah,
                'Keterangan'       => $item->keterangan
            ]);
        }
        
        // Add barang dikembalikan
        foreach ($this->barangDikembalikan as $item) {
            $data->push([
                'Kategori Laporan' => '2. PENGEMBALIAN',
                'Tanggal'          => $item->tanggal,
                'Nama Barang'      => $item->nama_barang,
                'Barcode / Seri'   => $item->barcode ?? '-',
                'Jumlah'           => $item->jumlah,
                'Keterangan'       => $item->keterangan
            ]);
        }

        // Add barang baru masuk
        foreach ($this->barangBaruMasuk as $item) {
            $data->push([
                'Kategori Laporan' => '3. ASET DITAMBAHKAN',
                'Tanggal'          => $item->tanggal,
                'Nama Barang'      => $item->nama_barang,
                'Barcode / Seri'   => $item->barcode ?? '-',
                'Jumlah'           => $item->jumlah,
                'Keterangan'       => $item->keterangan
            ]);
        }
        
        return $data;
    }

    public function headings(): array
    {
        return [
            'Kategori Laporan',
            'Tanggal',
            'Nama Barang',
            'Barcode / Seri',
            'Jumlah',
            'Keterangan / Detail'
        ];
    }
}
