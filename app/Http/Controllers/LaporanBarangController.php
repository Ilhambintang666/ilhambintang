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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get date range from request or set default (current month)
        $dari = $request->input('dari', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->input('sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        // Query borrowings with date range
        $query = Borrowing::with('item');
        
        if ($dari) {
            $query->where('borrow_date', '>=', $dari);
        }
        
        if ($sampai) {
            $query->where('borrow_date', '<=', $sampai);
        }
        
        $borrowings = $query->orderBy('borrow_date', 'desc')->get();
        
        // barang keluar = items borrowed during the period
        $barangKeluar = $borrowings->map(function ($b) {
            return (object) [
                'tanggal' => Carbon::parse($b->borrow_date)->format('d-m-Y'),
                'nama_barang' => $b->item ? $b->item->name : 'N/A',
                'jumlah' => 1,
                'keterangan' => 'Peminjaman oleh ' . $b->borrower_name
            ];
        });
        
        // barang masuk = items returned during the period
        $queryMasuk = Borrowing::with('item')
            ->whereNotNull('return_date');
        
        if ($dari) {
            $queryMasuk->where('return_date', '>=', $dari);
        }
        
        if ($sampai) {
            $queryMasuk->where('return_date', '<=', $sampai);
        }
        
        $returnedItems = $queryMasuk->orderBy('return_date', 'desc')->get();
        
        $barangMasuk = $returnedItems->map(function ($b) {
            return (object) [
                'tanggal' => Carbon::parse($b->return_date)->format('d-m-Y'),
                'nama_barang' => $b->item ? $b->item->name : 'N/A',
                'jumlah' => 1,
                'keterangan' => 'Dikembalikan oleh ' . $b->borrower_name
            ];
        });
        
        return view('auth.admin.peminjaman.barang', compact('barangMasuk', 'barangKeluar', 'dari', 'sampai'));
    }

    /**
     * Generate PDF report
     */
    public function pdf(Request $request)
    {
        $dari = $request->input('dari', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->input('sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        // Get barang keluar
        $query = Borrowing::with('item');
        
        if ($dari) {
            $query->where('borrow_date', '>=', $dari);
        }
        
        if ($sampai) {
            $query->where('borrow_date', '<=', $sampai);
        }
        
        $borrowings = $query->orderBy('borrow_date', 'desc')->get();
        
        $barangKeluar = $borrowings->map(function ($b) {
            return (object) [
                'tanggal' => Carbon::parse($b->borrow_date)->format('d-m-Y'),
                'nama_barang' => $b->item ? $b->item->name : 'N/A',
                'jumlah' => 1,
                'keterangan' => 'Peminjaman oleh ' . $b->borrower_name
            ];
        });
        
        // Get barang masuk
        $queryMasuk = Borrowing::with('item')
            ->whereNotNull('return_date');
        
        if ($dari) {
            $queryMasuk->where('return_date', '>=', $dari);
        }
        
        if ($sampai) {
            $queryMasuk->where('return_date', '<=', $sampai);
        }
        
        $returnedItems = $queryMasuk->orderBy('return_date', 'desc')->get();
        
        $barangMasuk = $returnedItems->map(function ($b) {
            return (object) [
                'tanggal' => Carbon::parse($b->return_date)->format('d-m-Y'),
                'nama_barang' => $b->item ? $b->item->name : 'N/A',
                'jumlah' => 1,
                'keterangan' => 'Dikembalikan oleh ' . $b->borrower_name
            ];
        });
        
        $pdf = PDF::loadView('auth.admin.peminjaman.barang-pdf', compact('barangMasuk', 'barangKeluar', 'dari', 'sampai'));
        
        return $pdf->download('laporan-peminjaman-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Generate Excel report
     */
    public function excel(Request $request)
    {
        $dari = $request->input('dari', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->input('sampai', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        // Get barang keluar
        $query = Borrowing::with('item');
        
        if ($dari) {
            $query->where('borrow_date', '>=', $dari);
        }
        
        if ($sampai) {
            $query->where('borrow_date', '<=', $sampai);
        }
        
        $borrowings = $query->orderBy('borrow_date', 'desc')->get();
        
        $barangKeluar = $borrowings->map(function ($b) {
            return (object) [
                'tanggal' => Carbon::parse($b->borrow_date)->format('d-m-Y'),
                'nama_barang' => $b->item ? $b->item->name : 'N/A',
                'jumlah' => 1,
                'keterangan' => 'Peminjaman oleh ' . $b->borrower_name
            ];
        });
        
        // Get barang masuk
        $queryMasuk = Borrowing::with('item')
            ->whereNotNull('return_date');
        
        if ($dari) {
            $queryMasuk->where('return_date', '>=', $dari);
        }
        
        if ($sampai) {
            $queryMasuk->where('return_date', '<=', $sampai);
        }
        
        $returnedItems = $queryMasuk->orderBy('return_date', 'desc')->get();
        
        $barangMasuk = $returnedItems->map(function ($b) {
            return (object) [
                'tanggal' => Carbon::parse($b->return_date)->format('d-m-Y'),
                'nama_barang' => $b->item ? $b->item->name : 'N/A',
                'jumlah' => 1,
                'keterangan' => 'Dikembalikan oleh ' . $b->borrower_name
            ];
        });
        
        // Generate Excel using simple export
        return Excel::download(new \App\Exports\LaporanPeminjamanExport($barangMasuk, $barangKeluar), 'laporan-peminjaman-' . date('Y-m-d') . '.xlsx');
    }
}
