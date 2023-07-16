<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'username' => ['required', 'string', 'unique:users'],
            'email' => ['required', 'email', 'unique:users'],
            'role' => ['required', 'string'],
            'gender' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect('/admin/users/create')
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();
        $input['password'] = bcrypt($request->email);
        User::create($input);

        return redirect('admin/users')->with('status', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);

        return view('users.edit', compact('user', 'id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'username' => ['required', 'string', 'unique:users,username,' . $user->id],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'role' => ['required', 'string'],
            'gender' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect('/admin/users/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        }

        $user->update($request->all());

        return redirect('/admin/users')->with('status', 'Data berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $username)
    {
        $user = User::where('username', $username)->first();

        if ($user) {
            $user->rooms()->delete();
            $user->borrows()->delete();
            $user->delete();
        }

        if (Gate::allows('admin')) {
            return redirect('/admin/users')->with('status', 'Data berhasil dihapus');
        } elseif (Gate::allows('operator')) {
            return redirect('/oprator/users')->with('status', 'Data berhasil dihapus');
        } else {
            abort(403, 'Unauthorized');
        }
    }

    public function resetPassword(User $user)
    {
        // Generate a new password
        $newPassword = 'invsch@garut';

        // Update the user's password
        $user->password = Hash::make($newPassword);
        $user->save();

        // Send an email or notification to the user with the new password

        return redirect()->back()->with('status', 'Password has been reset successfully.');
    }
}
