<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * =============================
     * SHOW ALL USERS
     * =============================
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $users = $query->latest()->paginate(10);

        return view('users', compact('users'));
    }

    /**
     * =============================
     * SHOW EDIT PAGE
     * =============================
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('users.edit', compact('user'));
    }

    /**
     * =============================
     * UPDATE USER
     * =============================
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect('/users')->with('success', 'User updated successfully');
    }

    /**
     * =============================
     * DELETE USER
     * =============================
     */
    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return back()->with('success', 'User deleted successfully');
    }
}


