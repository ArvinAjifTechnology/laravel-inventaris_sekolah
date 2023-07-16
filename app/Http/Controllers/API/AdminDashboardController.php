<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Borrow;
use App\Models\Item;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $data['auth'] = Auth::user();
        $data['auth_name'] = Auth::user()->full_name;
        $data['auth_code'] = Auth::user()->user_code;
        $data['auth_role'] = Auth::user()->role;
        $data['user_count'] = User::latest()->count();
        $data['room_count'] = Room::latest()->count();
        $data['item_count'] = Item::latest()->count();
        $data['borrow_count'] = Borrow::latest()->count();

        return response()->json([
            'success' => true,
            'message' => 'data ditemukan',
            'data' => $data,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
