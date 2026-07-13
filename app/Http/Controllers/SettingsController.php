<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function index()
    {
        $user = auth()->user()->load('role');
        return view('settings.index', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'profile_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return redirect()->route('settings.index')->with('toast', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Profil berhasil diperbarui.',
        ]);
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.'])->withInput();
        }

        $user->update(['password' => Hash::make($validated['password'])]);

        return redirect()->route('settings.index')->with('toast', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Password berhasil diubah.',
        ]);
    }
}