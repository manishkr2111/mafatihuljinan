<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(2000);

        return view('admin.users.index', compact('users'));
    }

    // UserController.php
    public function show(User $user)
    {
        return view('admin.users.details', compact('user'));
    }

    // Show the edit form
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // Update user data
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string|in:admin,editor,subscriber',
            'password' => 'nullable|string|min:6',
        ]);

        if(Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'you do not have permission to update users.');
        }
        if(Auth::user()->id === $user->id && $request->email !== $user->email) {
            return redirect()->back()->with('error', 'you cannot change your own email.');
        }
        if(Auth::user()->id === $user->id && $request->role !== $user->role) {
            return redirect()->back()->with('error', 'you cannot change your own role.');
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Update email verified at
        $user->email_verified_at = $request->has('email_verified') ? now() : null;

        $user->save();

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}
