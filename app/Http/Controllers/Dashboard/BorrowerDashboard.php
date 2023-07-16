<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BorrowerDashboard extends Controller
{
    public function index()
    {
        $user_id = auth()->user()->id;
        $borrowed_quantity = DB::select("SELECT COUNT(borrow_code) AS borrowed_quantity FROM borrows")[0]->borrowed_quantity;
        $room_count = DB::select("SELECT COUNT(room_code) AS room_count WHERE user_id $user_id FROM rooms")[0]->room_count;
        $item_count = Item::whereHas('room', function ($query) {
            $query->where('user_id', Auth::id());
        })->get();

        return view('dashboard.index', [
            'borrowed_quantity' => $borrowed_quantity,
            'room_count' => $room_count,
            'item_count' => $item_count,
        ]);
    }
}
