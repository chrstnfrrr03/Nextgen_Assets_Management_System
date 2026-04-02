<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::latest()->get();

        return view('departments', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        Department::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return back()->with('success', 'Department created');
    }

    public function destroy($id)
    {
        Department::findOrFail($id)->delete();

        return back()->with('success', 'Department deleted');
    }
}