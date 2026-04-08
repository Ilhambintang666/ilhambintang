<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Borrowing;

class AdminLoanController extends Controller
{
    public function index()
    {
        $loans = Loan::with(['user', 'item'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('auth.admin.peminjaman.index', compact('loans'));
    }

    public function returnIndex()
    {
        $loans = Loan::with(['user', 'item'])
            ->where('status', 'return_pending')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('auth.admin.peminjaman.returns', compact('loans'));
    }

    public function approve($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update(['status' => 'approved', 'approved_at' => now()]);
        $loan->item->update(['status' => 'dipinjam']);

        // Create borrowing record
        Borrowing::create([
            'borrower_name' => $loan->user->name,
            'item_id' => $loan->item_id,
            'borrow_date' => now()->toDateString(),
            'expected_return_date' => $loan->expected_return_date,
            'status' => 'dipinjam',
            'notes' => 'Approved loan request'
        ]);

        return back()->with('success', 'Peminjaman disetujui');
    }

    public function reject($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update(['status' => 'rejected']);

        return back()->with('success', 'Peminjaman ditolak');
    }

    public function confirmReturn($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update([
            'status' => 'returned',
            'returned_at' => now()
        ]);
        $loan->item->update(['status' => 'tersedia']);

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
