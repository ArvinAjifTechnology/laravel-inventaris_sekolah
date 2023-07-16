<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Borrow;
use App\Charts\ItemsChart;
use App\Charts\UsersChart;
use App\Charts\BorrowsChart;
use App\Charts\RevenueChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    public function index(UsersChart $usersChart, BorrowsChart $borrowsChart, RevenueChart $revenueChart, ItemsChart $itemsChart)
    {
        $revenue = DB::select("SELECT SUM(sub_total) AS revenue FROM borrows")[0]->revenue;
        $borrowed_quantity = DB::select("SELECT COUNT(borrow_code) AS borrowed_quantity FROM borrows")[0]->borrowed_quantity;
        $user_count = DB::select("SELECT COUNT(user_code) AS user_count FROM users")[0]->user_count;
        $item_count = DB::select("SELECT COUNT(item_code) AS item_count FROM items")[0]->item_count;
        $room_count = DB::select("SELECT COUNT(room_code) AS room_count FROM rooms")[0]->room_count;
        $currentWeekData = Borrow::getCurrentWeekData();
        $previousWeekData = Borrow::getPreviousWeekData();
        $today = Borrow::getCurrentDayData();
        return view('dashboard.index', [
            'revenue' => $revenue,
            'borrowed_quantity' => $borrowed_quantity,
            'user_count' => $user_count,
            'item_count' => $item_count,
            'room_count' => $room_count,
            'currentWeekData' => $currentWeekData,
            'previousWeekData' => $previousWeekData,
            'today' => $today,
            'usersChart' => $usersChart->build(),
            'borrowsChart' => $borrowsChart->build(),
            'revenueChart' => $revenueChart->build(),
            'itemsChart' => $itemsChart->build(),
            // 'borrowChart'
        ]);
    }
}
