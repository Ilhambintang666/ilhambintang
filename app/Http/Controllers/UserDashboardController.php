<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Item;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get active loans (approved but not returned)
        $activeLoans = Loan::where('user_id', $user->id)
            ->where('status', 'approved')
            ->with('item')
            ->get();

        // Get pending returns (return_pending status)
        $pendingReturns = Loan::where('user_id', $user->id)
            ->where('status', 'return_pending')
            ->with('item')
            ->get();

        // Get returned loans
        $returnedLoans = Loan::where('user_id', $user->id)
            ->where('status', 'returned')
            ->with('item')
            ->get();

        // Get available items (only loanable items)
        $availableItems = Item::where('status', 'tersedia')
            ->where('is_loanable', true)
            ->with(['category', 'location'])
            ->get();

        return view('user.dashboard', compact('user', 'activeLoans', 'pendingReturns', 'returnedLoans', 'availableItems'));
    }

    public function borrow()
    {
        $availableItems = Item::where('status', 'tersedia')
            ->where('is_loanable', true)
            ->with(['category', 'location'])
            ->get();
        return view('user.borrow', compact('availableItems'));
    }

    public function myBorrowings()
    {
        $user = auth()->user();
        $loans = Loan::where('user_id', $user->id)
            ->with('item')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.my-borrowings', compact('loans'));
    }

    public function profile()
    {
        $user = auth()->user();
        return view('user.profile', compact('user'));
    }

    public function myReturns()
    {
        $user = auth()->user();
        $returnRequests = Loan::where('user_id', $user->id)
            ->whereIn('status', ['return_pending', 'returned', 'rejected'])
            ->with('item')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('user.my-returns', compact('returnRequests'));
    }

    public function showReturnForm(Loan $loan)
    {
        if ($loan->user_id !== auth()->id()) {
            abort(403);
        }

        if ($loan->status !== 'approved') {
            return redirect()->route('user.my-borrowings')->with('error', 'Barang ini tidak dapat dikembalikan.');
        }

        return view('user.return', compact('loan'));
    }
}
