<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'data ditemukan',
            'data' => $users
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required|unique:users,username,except,id',
            'email' => 'required|email|unique:users,email,except,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Ada Kesalahan',
                'data' => $validator->errors()
            ], 401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($request->email);
        $users = User::create($input);

        return response()->json([
            'success' => true,
            'message' => 'data berhasil ditambahkan',
            'data' => $users
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user = User::latest()->where('id', $user->id)->first();

        return response()->json([
            'success' => true,
            'message' => 'data ditemukan',
            'data' => $user
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'username' => ['required', 'unique:users,username,except,id'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Ada Kesalahan',
                'data' => $validator->errors()
            ], 400);
        }
        $user = User::find($user->id);
        $input = $request->all();
        // $input['password'] = bcrypt($request->email);
        $users = $user->update($input);

        return response()->json([
            'success' => true,
            'message' => 'data ditemukan',
            'data' => $users
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'data berhasil dihapus',
            'data' => $user
        ], 200);
    }
}
