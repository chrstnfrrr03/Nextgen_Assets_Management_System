<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Password::min(6)],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'remove_profile_photo' => ['nullable', 'boolean'],
        ]);

        if ($request->boolean('remove_profile_photo') && $user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
            $user->profile_photo = null;
        }

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $validated['profile_photo'] = $request->file('profile_photo')->store('profiles', 'public');
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        unset($validated['remove_profile_photo']);

        $user->update($validated);

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Profile updated successfully.');
    }
}