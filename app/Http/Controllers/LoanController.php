<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Item;

class LoanController extends Controller
{


    public function borrow(Request $request)
    {
        $request->validate([
            'item_id'              => 'required|exists:items,id',
            'borrow_date'          => 'required|date',
            'expected_return_date' => 'required|date|after:borrow_date',
        ]);

        $item = Item::find($request->item_id);

        if (!$item->is_loanable) {
            return back()->with('error', 'Barang ini adalah barang paten dan tidak dapat dipinjam!');
        }

        if ($item->status !== 'tersedia') {
            return back()->with('error', 'Barang tidak tersedia untuk dipinjam!');
        }

        Loan::create([
            'user_id'              => auth()->id(),
            'item_id'              => $request->item_id,
            'status'               => 'pending',
            'borrow_date'          => $request->borrow_date,
            'expected_return_date' => $request->expected_return_date,
        ]);

        return back()->with('success', 'Permintaan peminjaman telah dikirim dan menunggu persetujuan admin.');
    }

    public function submitReturn(Request $request, Loan $loan)
    {
        if ($loan->user_id !== auth()->id()) {
            abort(403);
        }

        // Remove image requirement - now just process the return directly
        $loan->update([
            'status' => 'dikembalikan',
            'returned_at' => now(),
        ]);

        // Update item status back to available
        $loan->item->update(['status' => 'tersedia']);

        return back()->with('success', 'Barang berhasil dikembalikan!');
    }

    public function return(Request $request, $id)
    {
        $request->validate([
            'return_photo' => 'required|image|max:2048'
        ]);

        $path = $request->file('return_photo')->store('returns', 'public');

        Loan::findOrFail($id)->update([
            'status' => 'return_pending',
            'return_photo' => $path
        ]);

        return back()->with('success', 'Pengembalian diajukan');
    }

    /**
     * Bulk borrow multiple items at once
     */
    public function bulkBorrow(Request $request)
    {
        $request->validate([
            'items'                => 'required|array|min:1',
            'items.*'              => 'exists:items,id',
            'borrow_date'          => 'required|date',
            'expected_return_date' => 'required|date|after:borrow_date',
        ]);

        $items = $request->items;
        $quantities = $request->quantities ?? [];
        $createdLoans = [];
        $failedItems = [];

        foreach ($items as $itemId) {
            $item = Item::find($itemId);

            // Cek apakah barang bisa dipinjam
            if (!$item->is_loanable) {
                $failedItems[] = $item->name . ' (barang paten, tidak dapat dipinjam)';
                continue;
            }

            // Check if item is available
            if ($item->status !== 'tersedia') {
                $failedItems[] = $item->name . ' (tidak tersedia)';
                continue;
            }

            // Create loan record
            Loan::create([
                'user_id'              => auth()->id(),
                'item_id'              => $itemId,
                'status'               => 'pending',
                'borrow_date'          => $request->borrow_date,
                'expected_return_date' => $request->expected_return_date,
            ]);

            $createdLoans[] = $item->name;
        }

        // Prepare response message
        if (count($createdLoans) > 0 && count($failedItems) === 0) {
            return redirect()->route('user.my-borrowings')
                ->with('success', count($createdLoans) . ' peminjaman berhasil diajukan: ' . implode(', ', $createdLoans));
        } elseif (count($createdLoans) > 0 && count($failedItems) > 0) {
            return redirect()->route('user.my-borrowings')
                ->with('warning', count($createdLoans) . ' peminjaman berhasil diajukan, namun ' . count($failedItems) . ' gagal: ' . implode(', ', $failedItems));
        } else {
            return back()->with('error', 'Gagal mengajukan peminjaman. Semua barang yang dipilih tidak tersedia.');
        }
    }

    /**
     * Bulk return multiple items at once
     */
    public function bulkReturn(Request $request)
    {
        $request->validate([
            'loan_ids' => 'required|array|min:1',
            'loan_ids.*' => 'exists:loans,id',
            'return_photo' => 'required|image|max:2048'
        ]);

        $loanIds = $request->loan_ids;
        $loans = Loan::whereIn('id', $loanIds)
            ->where('user_id', auth()->id())
            ->whereIn('status', ['disetujui', 'approved'])
            ->with('item')
            ->get();

        if ($loans->count() === 0) {
            return back()->with('error', 'Tidak ada peminjaman yang disetujui untuk dikembalikan!');
        }

        $path = $request->file('return_photo')->store('returns', 'public');
        $successCount = 0;

        foreach ($loans as $loan) {
            // Update loan status ke return_pending
            $loan->update([
                'status' => 'return_pending',
                'return_photo' => $path
            ]);
            
            $successCount++;
        }

        $message = "{$successCount} barang diajukan untuk pengembalian dan menunggu verifikasi admin";

        return redirect()->route('user.my-borrowings')->with('success', $message . '!');
    }
}
