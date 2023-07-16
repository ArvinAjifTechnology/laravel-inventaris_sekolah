<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmitBorrowRequest;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\User;
use App\Models\Borrow;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Jobs\SendReturnReminderEmail;
use App\Rules\SufficientQuantityRule;
use App\Notifications\BorrowNotification;
use Illuminate\Support\Facades\Validator;
use App\Notifications\VerifiedSubmitBorrowRequestNotification;

class BorrowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Gate::allows('admin')) {
            $borrows = DB::select("SELECT borrows.*, items.item_name, CONCAT(users.first_name, ' ', users.last_name) AS full_name
                                    FROM borrows
                                    LEFT JOIN items ON borrows.item_id = items.id
                                    LEFT JOIN users ON borrows.user_id = users.id
                                    ORDER BY borrows.created_at DESC");
        } elseif (Gate::allows('operator')) {
            $borrows = DB::select("SELECT borrows.*, items.item_name, CONCAT(users.first_name, ' ', users.last_name) AS full_name
                                    FROM borrows
                                    LEFT JOIN items ON borrows.item_id = items.id
                                    LEFT JOIN users ON borrows.user_id = users.id
                                    ORDER BY borrows.created_at DESC");
        } elseif (Gate::allows('borrower')) {
            $user_id = Auth::user()->id;
            $borrows = DB::select("SELECT borrows.*, items.item_name, CONCAT(users.first_name, ' ', users.last_name) AS full_name
                                    FROM borrows
                                    LEFT JOIN items ON borrows.item_id = items.id
                                    LEFT JOIN users ON borrows.user_id = users.id
                                    WHERE borrows.user_id = $user_id
                                    ORDER BY borrows.created_at DESC");
        } else {
            abort(403, 'Unauthorized');
        }



        return view('borrows.index', compact('borrows'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $items = DB::select('SELECT * FROM items WHERE `condition` IN (?, ?)', ['good', 'fair']);
        $users = DB::select('SELECT * , CONCAT(users.first_name, " ", users.last_name) AS user_full_name FROM users WHERE role = ?', ['borrower']);
        // $items = DB::select("SELECT * FROM items WHERE condition = 'good'");
        // $users = DB::select("SELECT * FROM users WHERE role = 'borrower'");


        return view('borrows.create', compact('items', 'users'));
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


        if (Gate::allows('admin')) {
            if ($validator->fails()) {
                return redirect('/admin/borrows/create')
                    ->withErrors($validator)
                    ->withInput();
            }
        } elseif (Gate::allows('operator')) {
            if ($validator->fails()) {
                return redirect('/operator/borrows/create')
                    ->withErrors($validator)
                    ->withInput();
            }
        } else {
            abort(403, 'Unauthorized');
        }


        // Borrow::insert($request);

        // $item = Item::find($borrow->item_id);
        // $item->quantity += 1;
        // $item->save();

        $item = Item::find($request->input('item_id'));
        if ($item->quantity > 0) {
            $borrow = Borrow::createBorrow($request);
            $item->quantity -= $request->input('borrow_quantity');
            $item->save();
            $borrow->user->notify(new BorrowNotification($borrow));
            if (Gate::allows('admin')) {
                return redirect('/admin/borrows')->withErrors($validator)->withInput()->with('status', 'Selamat Data Berhasil Di Tambahkan');
            } elseif (Gate::allows('operator')) {
                return redirect('/operator/borrows')->withErrors($validator)->withInput()->with('status', 'Selamat Data Berhasil Di Tambahkan');
            } else {
                abort(403, 'Unauthorized');
            }
            // return redirect('/borrows')->withErrors($validator)->withInput()->with('status', 'Selamat Data Berhasil Di Tambahkan');
        } else {
            if (Gate::allows('admin')) {
                return redirect('admin/borrows/create')->withErrors(['error' => 'Stok barang habis'])->withInput();
            } elseif (Gate::allows('operator')) {
                return redirect('operator/borrows/create')->withErrors(['error' => 'Stok barang habis'])->withInput();
            } else {
                abort(403, 'Unauthorized');
            }
        }


        // return redirect('/borrows')->withErrors($validator)->withInput()->with('status', 'Selamat Data Berhasil Di Tambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (Gate::allows('admin')) {
            $borrow = DB::selectOne("
                SELECT borrows.*, users.*, items.*
                FROM borrows
                JOIN users ON borrows.user_id = users.id
                JOIN items ON borrows.item_id = items.id
                WHERE borrows.id = :id
            ", ['id' => $id]);
        } elseif (Gate::allows('operator')) {
            $borrow = DB::selectOne("
                SELECT borrows.*, users.*, items.*
                FROM borrows
                JOIN users ON borrows.user_id = users.id
                JOIN items ON borrows.item_id = items.id
                WHERE borrows.id = :id
            ", ['id' => $id]);
        } else {
            abort(403, 'Unauthorized');
        }


        return view('borrows.show', compact('borrow'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // $borrow = Borrow::find($id);
        // $items = Item::all()->where('condition', '=', 'good');
        // $users = User::all()->where('role', '=', 'borrower');
        // return view('borrows.edit', compact('borrow', 'items', 'users'));
        $borrow = DB::selectOne("SELECT * FROM borrows WHERE id = $id");
        $items = DB::select("SELECT * FROM items WHERE `condition` = 'good'");
        $users = DB::select("SELECT *, CONCAT(users.first_name, ' ', users.last_name) AS full_name FROM users WHERE role = 'borrower'");

        return view('borrows.edit', compact('borrow', 'items', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'borrow_date' => ['required', 'date', 'after_or_equal:today'],
            'return_date' => ['required', 'date', 'after:borrow_date'],
            'item_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'borrow_quantity' => ['required', 'integer', new SufficientQuantityRule],
        ]);

        if (Gate::allows('admin')) {
            if ($validator->fails()) {
                return redirect('/admin/borrows/' . $id . '/edit')
                    ->withErrors($validator)
                    ->withInput();
            }
        } elseif (Gate::allows('operator')) {
            if ($validator->fails()) {
                return redirect('/operator/borrows/' . $id . '/edit')
                    ->withErrors($validator)
                    ->withInput();
            }
        } else {
            abort(403, 'Unauthorized');
        }

        $item_id = $request->input('item_id');
        $item = DB::selectOne("SELECT * FROM items WHERE id = $item_id");
        if ($item->quantity > 0) {
            $borrow = Borrow::find($id); // Ambil data peminjaman yang akan diupdate

            $borrow->borrow_date = $request->input('borrow_date');
            $borrow->return_date = $request->input('return_date');
            $borrow->item_id = $request->input('item_id');
            $borrow->user_id = $request->input('user_id');
            // Dapatkan jumlah peminjaman sebelumnya
            $previousQuantity = $borrow->borrow_quantity;
            // $borrow->borrow_quantity = $request->input('borrow_quantity');
            $borrow->late_fee = 0;
            $borrow->total_rental_price = 0;
            $borrow->sub_total = 0;
            $borrow->borrow_status = 'borrowed';


            // dd($previousQuantity);
            // Update model Borrow dengan input pengguna
            $borrow->borrow_quantity = $request->input('borrow_quantity');

            // Dapatkan perubahan absolut pada jumlah peminjaman
            $absoluteChange = $borrow->borrow_quantity - $previousQuantity;

            // Dapatkan model Item yang terkait
            $item = $borrow->item;

            // Tentukan apakah akan menambah atau mengurangi stok barang
            if ($borrow->borrow_quantity > $previousQuantity) {
                // Jika jumlah peminjaman bertambah, tambahkan ke stok barang
                $item->quantity -= $absoluteChange;
                // dd($previousQuantity, $absoluteChange, $item->quantity);
            } elseif ($borrow->borrow_quantity < $previousQuantity) {
                // Jika jumlah peminjaman berkurang, kurangi dari stok barang
                $item->quantity -= $absoluteChange;
            }

            // Simpan perubahan pada model Borrow dan Item
            $borrow->save();
            $item->save();
            $borrow->user->notify(new BorrowNotification($borrow));

            if (Gate::allows('admin')) {
                return redirect('/admin/borrows')->with('status', 'Data berhasil diperbarui');
            } elseif (Gate::allows('operator')) {
                return redirect('/operator/borrows')->with('status', 'Data berhasil diperbarui');
            } else {
                abort(403, 'Unauthorized');
            }
        } else {
            if (Gate::allows('admin')) {
                return redirect('/admin/borrows/' . $id . '/edit')->withErrors(['error' => 'Stok barang habis'])->withInput();
            } elseif (Gate::allows('operator')) {
                return redirect('/operator/borrows/' . $id . '/edit')->withErrors(['error' => 'Stok barang habis'])->withInput();
            } else {
                abort(403, 'Unauthorized');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $borrow = DB::selectOne('SELECT * FROM borrows WHERE id = ?', [$id]);

        if ($borrow) {
            DB::delete('DELETE FROM borrows WHERE id = ?', [$id]);
        }

        return redirect('/items')->with('status', 'Data berhasil Di Hapus');
    }

    public function returnBorrow(Request $request, $id)
    {
        Borrow::returnItem($id);

        return redirect()->back()->with('status', 'Item returned successfully');
    }

    public function sendReturnReminder()
    {
        $borrows = Borrow::where('borrow_status', 'borrowed')->whereDate('return_date', Carbon::today())->get();

        foreach ($borrows as $borrow) {
            SendReturnReminderEmail::dispatch($borrow);
        }

        return response()->json(['message' => 'Return reminders sent successfully']);
    }

    public function submitBorrowRequest(Request $request, $borrow_code)
    {
        $borrow = DB::selectOne("SELECT borrows.*, CONCAT(users.first_name, ' ', users.last_name) AS full_name, users.* ,items.*
            FROM borrows
            JOIN users ON borrows.user_id = users.id
            JOIN items ON borrows.item_id = items.id
            WHERE borrows.borrow_code = '$borrow_code'
        ");

        // dd($borrow);
        $borrow_id = Borrow::where('borrow_code', '=', $borrow_code);
        // $item = Item::where('item_code', '=', $item_code)->first();
        // dd($request->all());
        $request->all();

        return view('borrows.verify-submit-borrow-request', compact('borrow'));
    }

    public function verifySubmitBorrowRequest(SubmitBorrowRequest $request, $borrow_code)
    {
        // dd($request->all());
        $item = Item::find($request->input('item_id'));
        if ($item->quantity > 0) {
            $borrow = Borrow::verifySubmitBorrowRequest($request, $borrow_code);
            $item->quantity -= $request->input('borrow_quantity');
            $item->save();
            $borrow->user->notify(new VerifiedSubmitBorrowRequestNotification($borrow));
            $borrow->user->notify(new BorrowNotification($borrow));
            if (Gate::allows('admin')) {
                return redirect('/admin/borrows')->with('status', 'Selamat Data Berhasil Di Tambahkan');
            } elseif (Gate::allows('operator')) {
                return redirect('/operator/borrows')->with('status', 'Selamat Data Berhasil Di Tambahkan');
            } else {
                abort(403, 'Unauthorized');
            }
            // return redirect('/borrows')->with('status', 'Selamat Data Berhasil Di Tambahkan');
        } else {
            if (Gate::allows('admin')) {
                return redirect()->back()->withErrors(['error' => 'Stok barang habis'])->withInput();
            } elseif (Gate::allows('operator')) {
                return redirect()->back()->withErrors(['error' => 'Stok barang habis'])->withInput();
            } else {
                abort(403, 'Unauthorized');
            }
        }
    }
    public function rejectBorrowRequest(Request $request, $id)
    {
        Borrow::rejectBorrowRequest($id);

        if (Gate::allows('admin')) {
            return redirect('/admin/borrows')->with('status', 'Selamat Data Berhasil Di Tambahkan');
        } elseif (Gate::allows('operator')) {
            return redirect('/operator/borrows')->with('status', 'Selamat Data Berhasil Di Tambahkan');
        } else {
            abort(403, 'Unauthorized');
        }
    }
}
