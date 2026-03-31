<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * =============================
     * DISPLAY CATEGORIES + SEARCH + FILTER
     * =============================
     */
    public function index(Request $request)
    {
        $query = Category::query();

        //  SEARCH (GROUPED FIX)
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        //  FILTER BY NAME
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        //  FILTER BY DESCRIPTION
        if ($request->filled('description')) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }

        $categories = $query->latest()
                            ->paginate(10)
                            ->withQueryString();

        return view('categories', compact('categories'));
    }

    /**
 * =============================
 * EDIT CATEGORY
 * =============================
 */
public function edit($id)
{
    $category = Category::findOrFail($id);

    return view('categories-edit', compact('category'));
}

/**
 * =============================
 * UPDATE CATEGORY
 * =============================
 */
public function update(Request $request, $id)
{
    $category = Category::findOrFail($id);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:255',
    ]);

    $category->update($validated);

    return redirect()->route('categories')
        ->with('success', 'Category updated successfully');
}
    /**
     * =============================
     * STORE CATEGORY
     * =============================
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        Category::create($validated);

        return back()->with('success', 'Category added successfully');
    }

    /**
     * =============================
     * DELETE CATEGORY
     * =============================
     */
    public function destroy($id)
    {
        Category::findOrFail($id)->delete();

        return back()->with('success', 'Category deleted successfully');
    }
}