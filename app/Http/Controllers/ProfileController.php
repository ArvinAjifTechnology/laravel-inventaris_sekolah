<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user(); // Mendapatkan data pengguna yang sedang login

        return view('profile.index', compact('user'));
    }

    public function edit()
    {
        $user = auth()->user(); // Mendapatkan data pengguna yang sedang login

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        // dd($request->all());
        $user = auth()->user(); // Mendapatkan data pengguna yang sedang login

        $validatedData = $request->validate([
            'username' => 'required|string|unique:users,username,' . $user->id,
            'user_code' => 'required|string|unique:users,user_code,' . $user->id,
            'email' => 'required|string|unique:users,email,' . $user->id,
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'gender' => 'required|string',
        ]);

        $user->update($validatedData);

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully.');
    }


    public function showChangePasswordForm()
    {
        return view('profile.change-password');
    }

    public function changePassword(Request $request)
    {
        $validator = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect('/dashboard')->with('success', 'Password updated successfully.')->withErrors($validator);
    }
}
