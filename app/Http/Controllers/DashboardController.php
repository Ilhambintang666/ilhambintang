<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Location;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik umum
        $totalItems = Item::count();
        $totalCategories = Category::count();
        $totalLocations = Location::count();
        $totalUsers = User::count();
        
        // Statistik barang berdasarkan status
        $availableItems = Item::where('status', 'tersedia')->count();
        $borrowedItems = Item::where('status', 'dipinjam')->count();
        $maintenanceItems = Item::where('status', 'maintenance')->count();
        
        // Peminjaman aktif (5 terbaru)
        $activeBorrowings = Borrowing::where('status', 'dipinjam')
            ->with(['user', 'item'])
            ->latest()
            ->take(5)
            ->get();
        
        // Peminjaman yang akan jatuh tempo (3 hari ke depan)
        $upcomingReturns = Borrowing::where('status', 'dipinjam')
            ->where('expected_return_date', '<=', Carbon::now()->addDays(3))
            ->where('expected_return_date', '>=', Carbon::now())
            ->with(['user', 'item'])
            ->orderBy('expected_return_date')
            ->get();
        
        // Peminjaman terlambat
        $overdueBorrowings = Borrowing::where('status', 'dipinjam')
            ->where('expected_return_date', '<', Carbon::now())
            ->with(['user', 'item'])
            ->orderBy('expected_return_date')
            ->get();
        
        // Barang dalam maintenance
        $maintenanceItemsList = Item::where('status', 'maintenance')
            ->with(['category', 'location'])
            ->latest()
            ->take(5)
            ->get();
        
        // Data untuk chart (peminjaman per bulan - 6 bulan terakhir)
        $borrowingStats = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthYear = $date->format('M Y');
            $count = Borrowing::whereMonth('borrow_date', $date->month)
                ->whereYear('borrow_date', $date->year)
                ->count();
            
            $borrowingStats[] = [
                'month' => $monthYear,
                'count' => $count
            ];
        }
        
        return view('dashboard', compact(
            'totalItems',
            'totalCategories', 
            'totalLocations',
            'totalUsers',
            'availableItems',
            'borrowedItems',
            'maintenanceItems',
            'activeBorrowings',
            'upcomingReturns',
            'overdueBorrowings',
            'maintenanceItemsList',
            'borrowingStats'
        ));
    }
}