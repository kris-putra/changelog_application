<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->latest()->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required','string','max:255','unique:users,username','regex:/^[a-zA-Z0-9_.\-]+$/'],
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
        ], [
            'username.unique' => 'Username sudah digunakan. Silakan gunakan username lain.',
            'username.regex' => 'Username hanya boleh berisi huruf, angka, underscore (_), dash (-), dan titik (.) tanpa spasi.',
        ]);

        $validated['username'] = strtolower($validated['username']);
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')->with('toast', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'User berhasil ditambahkan.',
        ]);
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => ['required','string','max:255','unique:users,username,' . $user->id,'regex:/^[a-zA-Z0-9_.\-]+$/'],
            'email' => 'required|email|unique:users,email,' . $user->id,
            'profile_name' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
        ], [
            'username.unique' => 'Username sudah digunakan. Silakan gunakan username lain.',
            'username.regex' => 'Username hanya boleh berisi huruf, angka, underscore (_), dash (-), dan titik (.) tanpa spasi.',
        ]);

        $validated['username'] = strtolower($validated['username']);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('toast', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'User berhasil diperbarui.',
        ]);
    }

    public function show(User $user)
    {
        $user->load('role');
        return view('users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('toast', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'User berhasil dihapus.',
        ]);
    }
}