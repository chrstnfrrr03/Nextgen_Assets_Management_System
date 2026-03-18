<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::latest()->get();
        return view('dashboard', compact('items'));
    }

    public function store(Request $request)
    {
        Item::create([
            'part_no' => $request->part_no,
            'brand' => $request->brand,
            'part_name' => $request->part_name,
            'description' => $request->description,
        ]);

        return back();
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $item->update($request->all());

        return back();
    }
}
