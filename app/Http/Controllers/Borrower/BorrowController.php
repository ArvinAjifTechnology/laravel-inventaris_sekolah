<?php

namespace App\Http\Controllers\Borrower;

use App\Models\Item;
use App\Models\Borrow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Rules\SufficientQuantityRule;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\SubmitBorrowRequest;
use App\Notifications\VerifySubmitBorrowRequestNotification;

class BorrowController extends Controller
{
    public function index()
    {
        // $borrows = Borrow::latest()->where('user_id', auth()->user()->id)->get();
        // $borrows = DB::select("SELECT * FROM borrows WHERE user_id = " . auth()->user()->id . " ORDER BY created_at DESC");
        $user_id = auth()->user()->id;
        $borrows = DB::select("SELECT borrows.*, items.item_name, CONCAT(users.first_name, ' ', users.last_name) AS full_name FROM borrows  JOIN items ON borrows.item_id = items.id  JOIN users ON borrows.user_id = users.id WHERE borrows.user_id = $user_id  ORDER BY borrows.created_at DESC");
        $borrows = collect($borrows);

        return view('borrower.index', compact('borrows'));
    }

    public function searchItemView()
    {
        $items = Item::query();
        // $items = DB::select('SELECT items.*, rooms.room_name FROM items LEFT JOIN rooms ON items.room_id = rooms.id');
        return view('borrower.search-item', compact('items'));
    }
    public function searchItem(Request $request)
    {
        $search = $request->search;
        // dd($search);
        // $query = Item::query();
        $query = "SELECT items.*, rooms.room_name FROM items LEFT JOIN rooms ON items.room_id = rooms.id WHERE items.`condition` IN (?, ?)";
        // Pencarian
        if ($search) {
            $query .= " AND (items.item_name LIKE '%$search%'
            OR items.item_code LIKE '%$search%'
            OR items.description LIKE '%$search%'
            OR items.`condition` LIKE '%$search%'
            OR items.rental_price LIKE '%$search%'
            OR items.late_fee_per_day LIKE '%$search%'
            OR items.quantity LIKE '%$search%'
            OR rooms.room_name LIKE '%$search%')";
        }

        $items = DB::select($query, ['good', 'fair']);
        // $query->where(function ($q) use ($search) {
        //     $q->where('item_name', 'LIKE', "%$search%")
        //         ->orWhere('item_code', 'LIKE', "%$search%")
        //         ->orWhere('description', 'LIKE', "%$search%")
        //         ->orWhere('condition', 'LIKE', "%$search%")
        //         ->orWhere('rental_price', 'LIKE', "%$search%")
        //         ->orWhere('late_fee_per_day', 'LIKE', "%$search%")
        //         ->orWhere('quantity', 'LIKE', "%$search%");
        // })
        //     ->orWhereHas('room', function ($q) use ($search) {
        //         $q->where('room_name', 'LIKE', "%$search%");
        //     });
        // }
        return view('borrower.search-item', compact('items'));
    }

    public function submitBorrowRequestView(Request $request, $item_code)
    {
        // Validasi dan logika lainnya...
        $item = DB::selectOne("SELECT * FROM items WHERE item_code = '$request->item_code'");

        return view('borrower.submit-borrow-request', compact('item', 'request'));
    }

    public function submitBorrowRequestViewTwo(Request $request)
    {
        // $item = Item::where('item_code', '=', $item_code)->first();
        $item = DB::selectOne("SELECT * FROM items WHERE id = '$request->item_id'");
        // dd($request->all());

        return view('borrower.submit-borrow-request-two', compact('item', 'request'));
    }

    public function verifySubmitBorrowRequestView(Request $request)
    {

        $item = DB::selectOne("SELECT * FROM items WHERE id = '$request->item_id'");
        // $item = Item::where('item_code', '=', $item_code)->first();
        // dd($request->all());
        $request->all();
        // $item = DB::selectOne("SELECT * FROM items WHERE item_code = '$item_code'");

        return view('borrower.verify-submit-borrow-request', compact('item', 'request'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'borrow_date' => ['required', 'date', 'after_or_equal:today'],
            'return_date' => ['required', 'date', 'after:borrow_date'],
            'item_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'borrow_quantity' => ['required', 'integer', new SufficientQuantityRule],
        ]);
        if ($validator->fails()) {
            return redirect('borrower/borrows/create/submit-borrow-request-verifiy')
                ->withErrors($validator)
                ->withInput();
        }
        $item = Item::find($request->input('item_id'));
        if ($item->quantity > 0) {
            $borrow = Borrow::submitBorrowRequest($request);
            $item->quantity -= 0;
            $item->save();
            $borrow->user->notify(new VerifySubmitBorrowRequestNotification($borrow));

            return redirect('/borrower/borrows')->with('status', 'Selamat Data Berhasil Di Tambahkan');
            // return redirect('/borrows')->withErrors($validator)->withInput()->with('status', 'Selamat Data Berhasil Di Tambahkan');
        } else {
            return redirect()->back()->withErrors(['error' => 'Stok barang habis'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $borrow = DB::selectOne("SELECT borrows.*, users.*, items.*
            FROM borrows
            JOIN users ON borrows.user_id = users.id
            JOIN items ON borrows.item_id = items.id
            WHERE borrows.user_id = :user_id AND borrows.id = :id
        ", ['user_id' => Auth::user()->id, 'id' => $id]);


        return view('borrows.show', compact('borrow'));
    }
}
