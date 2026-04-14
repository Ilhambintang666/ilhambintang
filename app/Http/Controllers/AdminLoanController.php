<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Borrowing;

class AdminLoanController extends Controller
{
    public function index()
    {
        $groups = \App\Models\LoanGroup::with(['user', 'loans.item'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $stockItems = \App\Models\Item::where('is_loanable', true)
            ->with(['category', 'location'])
            ->get()
            ->groupBy('name')
            ->map(function ($items) {
                $first = $items->first();
                return (object) [
                    'name' => $first->name,
                    'category' => $first->category,
                    'location' => $first->location,
                    'available_stock' => $items->where('status', 'tersedia')->count(),
                    'borrowed_stock' => $items->where('status', 'dipinjam')->count(),
                    'total_stock' => $items->count(),
                ];
            })
            ->values();

        return view('auth.admin.peminjaman.index', compact('groups', 'stockItems'));
    }

    public function returnIndex()
    {
        $loans = Loan::with(['user', 'item'])
            ->where('status', 'return_pending')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('auth.admin.peminjaman.returns', compact('loans'));
    }

    public function approveGroup($id)
    {
        $group = \App\Models\LoanGroup::with('loans.item', 'user')->findOrFail($id);
        $group->update(['status' => 'approved', 'approved_at' => now()]);

        foreach ($group->loans as $loan) {
            $loan->update(['status' => 'approved', 'approved_at' => now()]);
            $loan->item->update(['status' => 'dipinjam']);

            // Create borrowing record per item for legacy compatibility
            Borrowing::create([
                'borrower_name' => $group->user->name,
                'item_id' => $loan->item_id,
                'borrow_date' => $group->borrow_date,
                'expected_return_date' => $group->expected_return_date,
                'status' => 'dipinjam',
                'notes' => 'Approved loan request group'
            ]);
        }

        return back()->with('success', count($group->loans) . ' barang dalam transaksi ini berhasil disetujui.');
    }

    public function rejectGroup($id)
    {
        $group = \App\Models\LoanGroup::findOrFail($id);
        $group->update(['status' => 'rejected']);
        $group->loans()->update(['status' => 'rejected']);

        return back()->with('success', 'Transaksi peminjaman ditolak.');
    }

    public function confirmReturn($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update([
            'status' => 'returned',
            'returned_at' => now()
        ]);
        $loan->item->update(['status' => 'tersedia']);

        // Sync with Borrowing table (Daftar Transaksi)
        $borrowing = Borrowing::where('item_id', $loan->item_id)
            ->where('borrower_name', $loan->user->name)
            ->whereIn('status', ['dipinjam', 'disetujui'])
            ->first();
            
        if ($borrowing) {
            $borrowing->update([
                'status' => 'dikembalikan',
                'return_date' => now()
            ]);
        }

        return back()->with('success', 'Pengembalian dikonfirmasi');
    }

    public function approveReturn($id)
    {
        $loan = Loan::findOrFail($id);
        if ($loan->status !== 'return_pending') {
            return back()->with('error', 'Status peminjaman tidak valid untuk persetujuan pengembalian');
        }

        $loan->update([
            'status' => 'returned',
            'returned_at' => now()
        ]);
        $loan->item->update(['status' => 'tersedia']);

        // Sync with Borrowing table (Daftar Transaksi)
        $borrowing = Borrowing::where('item_id', $loan->item_id)
            ->where('borrower_name', $loan->user->name)
            ->whereIn('status', ['dipinjam', 'disetujui'])
            ->first();
            
        if ($borrowing) {
            $borrowing->update([
                'status' => 'dikembalikan',
                'return_date' => now()
            ]);
        }

        return back()->with('success', 'Pengembalian disetujui');
    }

    public function rejectReturn($id)
    {
        $loan = Loan::findOrFail($id);
        if ($loan->status !== 'return_pending') {
            return back()->with('error', 'Status peminjaman tidak valid untuk penolakan pengembalian');
        }

        $loan->update(['status' => 'approved']); // Reset to approved status

        return back()->with('success', 'Pengembalian ditolak. User dapat mengajukan ulang.');
    }
}
