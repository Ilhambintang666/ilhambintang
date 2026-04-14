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
     * Bulk borrow by quantity per item name.
     * quantities[item_name] = qty
     */
    public function bulkBorrowByQuantity(Request $request)
    {
        $request->validate([
            'quantities'           => 'required|array|min:1',
            'borrow_date'          => 'required|date',
            'expected_return_date' => 'required|date|after:borrow_date',
        ]);

        $createdLoans  = [];
        $failedItems   = [];
        $totalCreated  = 0;

        // Group creation flag
        $loanGroup = null;

        foreach ($request->quantities as $itemName => $qty) {
            $qty = (int) $qty;
            if ($qty <= 0) {
                continue;
            }

            // Ambil unit tersedia berdasarkan nama barang
            $units = Item::where('name', $itemName)
                ->where('status', 'tersedia')
                ->where('is_loanable', true)
                ->limit($qty)
                ->get();

            if ($units->count() === 0) {
                $failedItems[] = "{$itemName} (tidak tersedia)";
                continue;
            }

            // Create LoanGroup once if we actually have items to borrow
            if (!$loanGroup) {
                $loanGroup = \App\Models\LoanGroup::create([
                    'user_id'              => auth()->id(),
                    'borrow_date'          => $request->borrow_date,
                    'expected_return_date' => $request->expected_return_date,
                    'status'               => 'pending',
                ]);
            }

            // Buat loan record untuk tiap unit, kaitkan ke grup
            foreach ($units as $unit) {
                Loan::create([
                    'loan_group_id'        => $loanGroup->id,
                    'user_id'              => auth()->id(),
                    'item_id'              => $unit->id,
                    'status'               => 'pending',
                    'borrow_date'          => $request->borrow_date,
                    'expected_return_date' => $request->expected_return_date,
                ]);
                $totalCreated++;
            }

            $createdLoans[] = $itemName . ' (' . $units->count() . ' unit)';
        }

        if ($totalCreated > 0 && count($failedItems) === 0) {
            return redirect()->route('user.my-borrowings')
                ->with('success', "✅ {$totalCreated} peminjaman berhasil diajukan dan menunggu persetujuan admin.");
        } elseif ($totalCreated > 0 && count($failedItems) > 0) {
            return redirect()->route('user.my-borrowings')
                ->with('warning', "{$totalCreated} peminjaman berhasil diajukan. Catatan: " . implode('; ', $failedItems));
        } else {
            return back()->with('error', 'Gagal mengajukan peminjaman. Tidak ada barang yang tersedia.');
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
