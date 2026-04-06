<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BorrowingController extends Controller
{
    public function index()
    {
        $borrowings = Borrowing::with(['user', 'item.category', 'item.location'])
                              ->orderBy('created_at', 'desc')
                              ->get();
        
        // Get unique borrower names for the filter dropdown
        $borrowerNames = Borrowing::whereNotNull('borrower_name')
            ->distinct()
            ->pluck('borrower_name')
            ->sort()
            ->toArray();
        
        // Get categories for the filter dropdown
        $categories = \App\Models\Category::orderBy('name')->get();
        
        return view('borrowings.index', compact('borrowings', 'borrowerNames', 'categories'));
    }

    public function create()
    {
        $items = Item::where('status', 'tersedia')
                    ->where('condition', 'baik')
                    ->with(['category', 'location'])
                    ->get();
        $users = User::all();
        return view('borrowings.create', compact('items', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
    'borrower_name' => 'required|string|max:255',
    'item_id' => 'required|exists:items,id',
    'borrow_date' => 'required|date',
    'expected_return_date' => 'required|date|after:borrow_date',
    ]);

        // Check if item is available
        $item = Item::find($request->item_id);
        if ($item->status !== 'tersedia') {
            return back()->with('error', 'Barang tidak tersedia untuk dipinjam!');
        }

        // Create borrowing record with status 'menunggu'
        Borrowing::create([
    'borrower_name' => $request->borrower_name,
    'item_id' => $request->item_id,
    'borrow_date' => $request->borrow_date,
    'expected_return_date' => $request->expected_return_date,
    'notes' => $request->notes,
    'status' => 'menunggu',
]);

        // Item status remains 'tersedia' until approved

        return redirect()->route('borrowings.index')->with('success', 'Peminjaman berhasil dicatat!');
    }

    public function show(Borrowing $borrowing)
    {
        $borrowing->load(['item.category', 'item.location']);
        
        // Attempt to fetch the corresponding Loan to get the return proof image
        $loan = \App\Models\Loan::where('item_id', $borrowing->item_id)
            ->whereHas('user', function($query) use ($borrowing) {
                $query->where('name', $borrowing->borrower_name);
            })
            ->orderBy('created_at', 'desc')
            ->first();

        $return_photo = $loan ? $loan->return_photo : null;

        return view('borrowings.show', compact('borrowing', 'return_photo'));
    }

    public function edit(Borrowing $borrowing)
    {
        $items = Item::where('status', 'tersedia')
                    ->orWhere('id', $borrowing->item_id)
                    ->with(['category', 'location'])
                    ->get();
        $users = User::all();
        return view('borrowings.edit', compact('borrowing', 'items', 'users'));
    }

    public function update(Request $request, Borrowing $borrowing)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'item_id' => 'required|exists:items,id',
            'borrow_date' => 'required|date',
            'expected_return_date' => 'required|date|after:borrow_date',
            'notes' => 'nullable|string'
        ]);

        // If item changed, update old and new item status
        if ($borrowing->item_id != $request->item_id) {
            // Set old item back to available
            $borrowing->item->update(['status' => 'tersedia']);
            
            // Set new item to borrowed
            $newItem = Item::find($request->item_id);
            $newItem->update(['status' => 'dipinjam']);
        }

        $borrowing->update($request->all());

        return redirect()->route('borrowings.index')->with('success', 'Peminjaman berhasil diupdate!');
    }

    public function destroy(Borrowing $borrowing)
    {
        // Set item back to available if approved but not returned yet
        if ($borrowing->status === 'disetujui') {
            $borrowing->item->update(['status' => 'tersedia']);
        }

        $borrowing->delete();
        return redirect()->route('borrowings.index')->with('success', 'Peminjaman berhasil dihapus!');
    }

    public function returnItem(Borrowing $borrowing)
    {
        if ($borrowing->status === 'dikembalikan') {
            return back()->with('error', 'Barang sudah dikembalikan!');
        }

        if ($borrowing->status !== 'disetujui') {
            return back()->with('error', 'Peminjaman belum disetujui!');
        }

        $now = Carbon::now();
        $status = $now->gt($borrowing->expected_return_date) ? 'terlambat' : 'dikembalikan';

        // Update borrowing
        $borrowing->update([
            'return_date' => $now,
            'status' => $status
        ]);

        // Update item status back to available
        $borrowing->item->update(['status' => 'tersedia']);

        $message = $status === 'terlambat' ?
                  'Barang berhasil dikembalikan (TERLAMBAT)!' :
                  'Barang berhasil dikembalikan tepat waktu!';

        return redirect()->route('borrowings.index')->with('success', $message);
    }

    /**
     * Bulk return multiple borrowings at once
     * Can return any combination of items - by same borrower, same category, or any selected items
     */
    public function bulkReturn(Request $request)
    {
        $request->validate([
            'borrowing_ids' => 'required|array|min:1',
            'borrowing_ids.*' => 'exists:borrowings,id'
        ]);

        $borrowingIds = $request->borrowing_ids;
        $borrowings = Borrowing::whereIn('id', $borrowingIds)
            ->with(['item', 'item.category'])
            ->get();

        // Validate that all borrowings are approved and not yet returned
        $invalidBorrowings = $borrowings->filter(function($b) {
            return $b->status !== 'disetujui';
        });

        if ($invalidBorrowings->count() > 0) {
            return back()->with('error', 'Beberapa peminjaman tidak dapat dikembalikan karena statusnya bukan "Disetujui"!');
        }

        // Removed restriction: now allows any combination of items to be returned together
        // Previously: validate that all borrowings have the same item name
        // Now: allows bulk return for same borrower, same category, or any combination

        $now = Carbon::now();
        $successCount = 0;
        $lateCount = 0;
        $itemsReturned = [];

        foreach ($borrowings as $borrowing) {
            $status = $now->gt($borrowing->expected_return_date) ? 'terlambat' : 'dikembalikan';
            
            if ($status === 'terlambat') {
                $lateCount++;
            }

            // Update borrowing
            $borrowing->update([
                'return_date' => $now,
                'status' => $status
            ]);

            // Update item status back to available
            $borrowing->item->update(['status' => 'tersedia']);
            
            $itemsReturned[] = $borrowing->item->name;
            $successCount++;
        }

        $message = "{$successCount} barang berhasil dikembalikan";
        if ($lateCount > 0) {
            $message .= " ({$lateCount} terlambat)";
        }

        return redirect()->route('borrowings.index')->with('success', $message . '!');
    }

    /**
     * Bulk return by same borrower
     */
    public function bulkReturnByBorrower(Request $request)
    {
        $request->validate([
            'borrower_name' => 'required|string|max:255'
        ]);

        $borrowerName = $request->borrower_name;
        
        $borrowings = Borrowing::where('borrower_name', $borrowerName)
            ->where('status', 'disetujui')
            ->with(['item', 'item.category'])
            ->get();

        if ($borrowings->count() === 0) {
            return back()->with('error', 'Tidak ada peminjaman yang disetujui untuk peminjam tersebut!');
        }

        $now = Carbon::now();
        $successCount = 0;
        $lateCount = 0;

        foreach ($borrowings as $borrowing) {
            $status = $now->gt($borrowing->expected_return_date) ? 'terlambat' : 'dikembalikan';
            
            if ($status === 'terlambat') {
                $lateCount++;
            }

            $borrowing->update([
                'return_date' => $now,
                'status' => $status
            ]);

            $borrowing->item->update(['status' => 'tersedia']);
            
            $successCount++;
        }

        $message = "{$successCount} barang berhasil dikembalikan dari peminjam {$borrowerName}";
        if ($lateCount > 0) {
            $message .= " ({$lateCount} terlambat)";
        }

        return redirect()->route('borrowings.index')->with('success', $message . '!');
    }

    /**
     * Bulk return by same category
     */
    public function bulkReturnByCategory(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id'
        ]);

        $categoryId = $request->category_id;
        
        $borrowings = Borrowing::whereHas('item', function($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->where('status', 'disetujui')
            ->with(['item', 'item.category'])
            ->get();

        if ($borrowings->count() === 0) {
            return back()->with('error', 'Tidak ada peminjaman yang disetujui untuk kategori tersebut!');
        }

        $now = Carbon::now();
        $successCount = 0;
        $lateCount = 0;
        $categoryName = $borrowings->first()->item->category->name;

        foreach ($borrowings as $borrowing) {
            $status = $now->gt($borrowing->expected_return_date) ? 'terlambat' : 'dikembalikan';
            
            if ($status === 'terlambat') {
                $lateCount++;
            }

            $borrowing->update([
                'return_date' => $now,
                'status' => $status
            ]);

            $borrowing->item->update(['status' => 'tersedia']);
            
            $successCount++;
        }

        $message = "{$successCount} barang berhasil dikembalikan dari kategori {$categoryName}";
        if ($lateCount > 0) {
            $message .= " ({$lateCount} terlambat)";
        }

        return redirect()->route('borrowings.index')->with('success', $message . '!');
    }

    public function approve(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'menunggu') {
            return back()->with('error', 'Peminjaman sudah diproses!');
        }

        // Check if item is still available
        if ($borrowing->item->status !== 'tersedia') {
            return back()->with('error', 'Barang tidak tersedia!');
        }

        $borrowing->update(['status' => 'disetujui']);
        $borrowing->item->update(['status' => 'dipinjam']);

        return redirect()->route('borrowings.index')->with('success', 'Peminjaman berhasil disetujui!');
    }

    public function reject(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'menunggu') {
            return back()->with('error', 'Peminjaman sudah diproses!');
        }

        $borrowing->update(['status' => 'ditolak']);

        return redirect()->route('borrowings.index')->with('success', 'Peminjaman berhasil ditolak!');
    }
}
