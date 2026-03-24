<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    // SHOW PAGE
    public function index()
    {
        $suppliers = Supplier::latest()->get();
        return view('suppliers', compact('suppliers'));
    }

    // STORE DATA
    public function store(Request $request)
    {
        Supplier::create([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Supplier added');
    }

    // DELETE
    public function destroy($id)
    {
        Supplier::findOrFail($id)->delete();
        return back()->with('success', 'Deleted');
    }
}
