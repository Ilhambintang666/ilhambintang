<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with(['category', 'location'])->get();
        return view('items.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::all();
        $locations = Location::all();
        return view('items.create', compact('categories', 'locations'));
    }

   public function store(Request $request)
{
    $name = $request->name;
    $quantity = $request->quantity;
    
    // Get prefix from request, default to PMI if empty
    $prefix = strtoupper(trim($request->prefix ?? 'PMI'));
    
    // Get the last item with this prefix
    $lastItemWithPrefix = Item::where('barcode', 'like', $prefix . '%')
        ->orderBy('barcode', 'desc')
        ->first();
    
    // Extract the last number for this prefix
    $lastNumber = 0;
    if ($lastItemWithPrefix) {
        // Remove the prefix and extract the number (e.g., "DNR-001" → "001" → 1)
        $barcodeNumber = str_replace($prefix . '-', '', $lastItemWithPrefix->barcode);
        $barcodeNumber = str_replace($prefix, '', $lastItemWithPrefix->barcode); // Fallback for old format
        $lastNumber = (int) preg_replace('/[^0-9]/', '', $barcodeNumber);
    }

    for ($i = 1; $i <= $quantity; $i++) {
        $newNumber = $lastNumber + $i;
        $barcode = $prefix . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        
        Item::create([
            'name'        => $name,
            'barcode'     => $barcode,
            'category_id' => $request->category_id,
            'location_id' => $request->location_id,
            'condition'   => 'baik',
            'status'      => 'tersedia',
            'quantity'    => 1, // setiap barang dihitung satuan
            'is_loanable' => $request->has('is_loanable') ? 1 : 0,
        ]);
    }

    return redirect()->route('items.index')->with('success', 'Barang berhasil ditambahkan.');
}


    public function show(Item $item)
    {
        $item->load(['category', 'location', 'borrowings.user']);
        return view('items.show', compact('item'));
    }

    public function edit(Item $item)
    {
        $categories = Category::all();
        $locations = Location::all();
        return view('items.edit', compact('item', 'categories', 'locations'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name'          => 'required|max:255',
            'barcode'       => 'required|unique:items,barcode,' . $item->id,
            'description'   => 'nullable',
            'category_id'   => 'required|exists:categories,id',
            'location_id'   => 'required|exists:locations,id',
            'condition'     => 'required|in:baik,rusak,dalam_perbaikan',
            'status'        => 'required|in:tersedia,dipinjam,maintenance',
            'quantity'      => 'required|integer|min:1',
            'purchase_date' => 'nullable|date',
            'price'         => 'nullable|numeric|min:0'
        ]);

        $item->update(array_merge($request->all(), [
            'is_loanable' => $request->has('is_loanable') ? 1 : 0,
        ]));
        return redirect()->route('items.index')->with('success', 'Barang berhasil diupdate!');
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Barang berhasil dihapus!');
    }
}