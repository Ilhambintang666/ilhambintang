<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Location;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use PDF;
use Excel;
use Carbon\Carbon;

class LaporanBarangController extends Controller
{
    private function getReportData($dari, $sampai)
    {
        // 1. Barang Dipinjam (Barang Keluar)
        $queryBorrow = Borrowing::with('item');
        if ($dari) $queryBorrow->where('borrow_date', '>=', $dari);
        if ($sampai) $queryBorrow->where('borrow_date', '<=', $sampai);
        
        $barangDipinjam = $queryBorrow->orderBy('borrow_date', 'desc')->get()->map(function ($b) {
            return (object) [
                'tanggal' => Carbon::parse($b->borrow_date)->format('d-m-Y'),
                'nama_barang' => $b->item ? $b->item->name : 'N/A',
                'jumlah' => 1,
                'keterangan' => 'Dipinjam oleh: ' . $b->borrower_name
            ];
        });

        // 2. Barang Dikembalikan
        $queryReturn = Borrowing::with('item')->whereNotNull('return_date');
        if ($dari) $queryReturn->where('return_date', '>=', $dari);
        if ($sampai) $queryReturn->where('return_date', '<=', $sampai);
        
        $barangDikembalikan = $queryReturn->orderBy('return_date', 'desc')->get()->map(function ($b) {
            return (object) [
                'tanggal' => Carbon::parse($b->return_date)->format('d-m-Y'),
                'nama_barang' => $b->item ? $b->item->name : 'N/A',
                'jumlah' => 1,
                'keterangan' => 'Dikembalikan oleh: ' . $b->borrower_name
            ];
        });

        // 3. Barang Ditambahkan (Barang Masuk)
        $queryNewItem = \App\Models\Item::query();
        if ($dari && $sampai) {
            $queryNewItem->where(function($q) use ($dari, $sampai) {
                $q->whereBetween('purchase_date', [$dari, $sampai])
                  ->orWhereBetween('created_at', [$dari . ' 00:00:00', $sampai . ' 23:59:59']);
            });
        } elseif ($dari) {
            $queryNewItem->where(function($q) use ($dari) {
                $q->where('purchase_date', '>=', $dari)
                  ->orWhere('created_at', '>=', $dari . ' 00:00:00');
            });
        } elseif ($sampai) {
            $queryNewItem->where(function($q) use ($sampai) {
                $q->where('purchase_date', '<=', $sampai)
                  ->orWhere('created_at', '<=', $sampai . ' 23:59:59');
            });
        }

        $barangBaruMasuk = $queryNewItem->orderBy('created_at', 'desc')->get()->map(function ($i) {
            $tgl = $i->purchase_date ? Carbon::parse($i->purchase_date)->format('d-m-Y') : Carbon::parse($i->created_at)->format('d-m-Y');
            $harga = $i->price ? 'Rp ' . number_format($i->price, 0, ',', '.') : '-';
            return (object) [
                'tanggal' => $tgl,
                'nama_barang' => $i->name,
                'jumlah' => 1, // tiap row 1 barang (bisa juga $i->quantity kalau ada logic grouping)
                'keterangan' => 'Kondisi: ' . ucfirst($i->condition) . ' | Harga: ' . $harga
            ];
        });

        return compact('barangDipinjam', 'barangDikembalikan', 'barangBaruMasuk');
    }

    public function index(Request $request)
    {
        $dari = $request->input('dari', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->input('sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        $data = $this->getReportData($dari, $sampai);
        $data['dari'] = $dari;
        $data['sampai'] = $sampai;
        
        return view('auth.admin.peminjaman.barang', $data);
    }

    public function pdf(Request $request)
    {
        $dari = $request->input('dari', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->input('sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        $data = $this->getReportData($dari, $sampai);
        $data['dari'] = $dari;
        $data['sampai'] = $sampai;
        
        $pdf = PDF::loadView('auth.admin.peminjaman.barang-pdf', $data);
        return $pdf->download('laporan-inventaris-lengkap-' . date('Y-m-d') . '.pdf');
    }

    public function excel(Request $request)
    {
        $dari = $request->input('dari', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->input('sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        $data = $this->getReportData($dari, $sampai);
        
        return Excel::download(new \App\Exports\LaporanPeminjamanExport(
            $data['barangDipinjam'], 
            $data['barangDikembalikan'], 
            $data['barangBaruMasuk']
        ), 'laporan-inventaris-lengkap-' . date('Y-m-d') . '.xlsx');
    }
}
