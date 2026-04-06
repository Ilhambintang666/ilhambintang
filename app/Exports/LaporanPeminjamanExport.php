<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanPeminjamanExport implements FromCollection, WithHeadings
{
    protected $barangMasuk;
    protected $barangKeluar;

    public function __construct($barangMasuk, $barangKeluar)
    {
        $this->barangMasuk = $barangMasuk;
        $this->barangKeluar = $barangKeluar;
    }

    public function collection()
    {
        // Combine both collections with a type indicator
        $data = collect();
        
        // Add barang keluar (Peminjaman)
        foreach ($this->barangKeluar as $item) {
            $data->push([
                'Jenis' => 'Peminjaman (Barang Keluar)',
                'Tanggal' => $item->tanggal,
                'Nama Barang' => $item->nama_barang,
                'Jumlah' => $item->jumlah,
                'Keterangan' => $item->keterangan
            ]);
        }
        
        // Add barang masuk (Pengembalian)
        foreach ($this->barangMasuk as $item) {
            $data->push([
                'Jenis' => 'Pengembalian (Barang Masuk)',
                'Tanggal' => $item->tanggal,
                'Nama Barang' => $item->nama_barang,
                'Jumlah' => $item->jumlah,
                'Keterangan' => $item->keterangan
            ]);
        }
        
        return $data;
    }

    public function headings(): array
    {
        return [
            'Jenis',
            'Tanggal',
            'Nama Barang',
            'Jumlah',
            'Keterangan'
        ];
    }
}
