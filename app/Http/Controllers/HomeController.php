<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $sum_of_sub_total = DB::select("SELECT SUM(sub_total) AS revenue FROM borrows")[0]->revenue;
        $borrowed_quantity = DB::select("SELECT COUNT(borrow_code) AS borrowed_quantity FROM borrows")[0]->borrowed_quantity;
        $user_count = DB::select("SELECT COUNT(user_code) AS user_count FROM users")[0]->user_count;
        return view('home', compact('sum_of_sub_total'));
    }
}
