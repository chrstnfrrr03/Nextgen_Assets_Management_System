<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class InventoryController extends Controller
{
    /**
     * Display all products (previously items)
     */
    public function index()
    {
        // Get latest products with pagination
        $items = Item::latest()->paginate(10);

        // Load view
        return view('items', compact('items'));
    }

    /**
     * Store new product
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'part_no' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'part_name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        // Create new product
        Item::create($validated);

        return redirect()->route('items')
            ->with('success', 'Product added successfully');
    }

    /**
     * Update product
     */
    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $validated = $request->validate([
            'part_no' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'part_name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $item->update($validated);

        return redirect()->route('items')
            ->with('success', 'Product updated successfully');
    }

    /**
     * Delete product
     */
    public function destroy($id)
    {
        Item::findOrFail($id)->delete();

        return redirect()->route('items')
            ->with('success', 'Product deleted successfully');
    }
}