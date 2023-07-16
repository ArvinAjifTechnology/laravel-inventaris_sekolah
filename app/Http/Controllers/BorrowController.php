<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmitBorrowRequest;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\User;
use App\Models\Borrow;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
            $borrows = Borrow::with(['item', 'user'])
                ->orderBy('created_at', 'DESC')
                ->get();
        } elseif (Gate::allows('operator')) {
            $borrows = Borrow::with(['item', 'user'])
                ->orderBy('created_at', 'DESC')
                ->get();
        } elseif (Gate::allows('borrower')) {
            $user_id = auth()->user()->id;
            $borrows = Borrow::with(['item', 'user'])
                ->where('user_id', $user_id)
                ->orderBy('created_at', 'DESC')
                ->get();
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
        $items = Item::whereIn('condition', ['good', 'fair'])->get();
        $users = User::where('role', 'borrower')->get();

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

        $item = Item::find($request->input('item_id'));

        if ($item->quantity > 0) {
            $borrow = Borrow::create([
                'borrow_date' => $request->input('borrow_date'),
                'return_date' => $request->input('return_date'),
                'item_id' => $request->input('item_id'),
                'user_id' => $request->input('user_id'),
                'borrow_quantity' => $request->input('borrow_quantity'),
                'late_fee' => 0,
                'total_rental_price' => 0,
                'sub_total' => 0,
                'borrow_status' => 'borrowed',
            ]);

            $item->quantity -= $request->input('borrow_quantity');
            $item->save();

            $borrow->user->notify(new BorrowNotification($borrow));

            if (Gate::allows('admin')) {
                return redirect('/admin/borrows')->with('status', 'Data berhasil ditambahkan');
            } elseif (Gate::allows('operator')) {
                return redirect('/operator/borrows')->with('status', 'Data berhasil ditambahkan');
            } else {
                abort(403, 'Unauthorized');
            }
        } else {
            if (Gate::allows('admin')) {
                return redirect('admin/borrows/create')->withErrors(['error' => 'Stok barang habis'])->withInput();
            } elseif (Gate::allows('operator')) {
                return redirect('operator/borrows/create')->withErrors(['error' => 'Stok barang habis'])->withInput();
            } else {
                abort(403, 'Unauthorized');
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (Gate::allows('admin')) {
            $borrow = Borrow::with(['user', 'item'])->find($id);
        } elseif (Gate::allows('operator')) {
            $borrow = Borrow::with(['user', 'item'])->find($id);
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
        $borrow = Borrow::find($id);
        $items = Item::where('condition', 'good')->get();
        $users = User::where('role', 'borrower')->get();

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

        $item = Item::find($request->input('item_id'));

        if ($item->quantity > 0) {
            $borrow = Borrow::find($id);
            $previousQuantity = $borrow->borrow_quantity;

            $borrow->borrow_date = $request->input('borrow_date');
            $borrow->return_date = $request->input('return_date');
            $borrow->item_id = $request->input('item_id');
            $borrow->user_id = $request->input('user_id');
            $borrow->borrow_quantity = $request->input('borrow_quantity');
            $borrow->late_fee = 0;
            $borrow->total_rental_price = 0;
            $borrow->sub_total = 0;
            $borrow->borrow_status = 'borrowed';

            $absoluteChange = $borrow->borrow_quantity - $previousQuantity;

            if ($borrow->borrow_quantity > $previousQuantity) {
                $item->quantity -= $absoluteChange;
            } elseif ($borrow->borrow_quantity < $previousQuantity) {
                $item->quantity -= $absoluteChange;
            }

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
        $borrow = Borrow::find($id);

        if ($borrow) {
            $borrow->delete();
        }

        return redirect('/items')->with('status', 'Data berhasil dihapus');
    }

    public function returnBorrow(Request $request, $id)
    {
        Borrow::returnItem($id);

        return redirect()->back()->with('status', 'Item returned successfully');
    }

    public function sendReturnReminder()
    {
        $borrows = Borrow::where('borrow_status', 'borrowed')
            ->whereDate('return_date', Carbon::today())
            ->get();

        foreach ($borrows as $borrow) {
            SendReturnReminderEmail::dispatch($borrow);
        }

        return response()->json(['message' => 'Return reminders sent successfully']);
    }

    public function submitBorrowRequest(Request $request, $borrow_code)
    {
        $borrow = Borrow::with(['user', 'item'])
            ->where('borrow_code', $borrow_code)
            ->firstOrFail();

        return view('borrows.verify-submit-borrow-request', compact('borrow'));
    }

    public function verifySubmitBorrowRequest(SubmitBorrowRequest $request, $borrow_code)
    {
        $item = Item::find($request->input('item_id'));

        if ($item->quantity > 0) {
            $borrow = Borrow::verifySubmitBorrowRequest($request, $borrow_code);
            $item->quantity -= $request->input('borrow_quantity');
            $item->save();
            $borrow->user->notify(new VerifiedSubmitBorrowRequestNotification($borrow));
            $borrow->user->notify(new BorrowNotification($borrow));

            if (Gate::allows('admin')) {
                return redirect('/admin/borrows')->with('status', 'Data berhasil ditambahkan');
            } elseif (Gate::allows('operator')) {
                return redirect('/operator/borrows')->with('status', 'Data berhasil ditambahkan');
            } else {
                abort(403, 'Unauthorized');
            }
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
            return redirect('/admin/borrows')->with('status', 'Data berhasil ditambahkan');
        } elseif (Gate::allows('operator')) {
            return redirect('/operator/borrows')->with('status', 'Data berhasil ditambahkan');
        } else {
            abort(403, 'Unauthorized');
        }
    }
}
