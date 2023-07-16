<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubmitBorrowRequest;
use App\Jobs\SendReturnReminderEmail;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\User;
use App\Models\Borrow;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;
use App\Rules\SufficientQuantityRule;
use App\Notifications\BorrowNotification;
use Illuminate\Support\Facades\Validator;
use App\Notifications\VerifiedSubmitBorrowRequestNotification;

class BorrowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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

        return response()->json(['borrows' => $borrows]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
                return response()->json(['errors' => $validator->errors()], 422);
            }
        } elseif (Gate::allows('operator')) {
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
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

            return response()->json(['message' => 'Data berhasil ditambahkan']);
        } else {
            return response()->json(['error' => 'Stok barang habis'], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
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

        return response()->json(['borrow' => $borrow]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
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
                return response()->json(['errors' => $validator->errors()], 422);
            }
        } elseif (Gate::allows('operator')) {
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
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

            return response()->json(['message' => 'Data berhasil diperbarui']);
        } else {
            return response()->json(['error' => 'Stok barang habis'], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id)
    {
        $borrow = Borrow::find($id);

        if ($borrow) {
            $borrow->delete();
        }

        return response()->json(['message' => 'Data berhasil dihapus']);
    }

    /**
     * Mark the specified borrow as returned.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function returnBorrow(Request $request, $id)
    {
        Borrow::returnItem($id);

        return response()->json(['message' => 'Item returned successfully']);
    }

    /**
     * Send return reminders for overdue borrows.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Submit a borrow request for verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $borrow_code
     * @return \Illuminate\Http\Response
     */
    public function submitBorrowRequest(Request $request, $borrow_code)
    {
        $borrow = Borrow::with(['user', 'item'])
            ->where('borrow_code', $borrow_code)
            ->firstOrFail();

        return response()->json(['borrow' => $borrow]);
    }

    /**
     * Verify and process a submitted borrow request.
     *
     * @param  \App\Http\Requests\SubmitBorrowRequest  $request
     * @param  string  $borrow_code
     * @return \Illuminate\Http\Response
     */
    public function verifySubmitBorrowRequest(SubmitBorrowRequest $request, $borrow_code)
    {
        $item = Item::find($request->input('item_id'));

        if ($item->quantity > 0) {
            $borrow = Borrow::verifySubmitBorrowRequest($request, $borrow_code);
            $item->quantity -= $request->input('borrow_quantity');
            $item->save();
            $borrow->user->notify(new VerifiedSubmitBorrowRequestNotification($borrow));
            $borrow->user->notify(new BorrowNotification($borrow));

            return response()->json(['message' => 'Data berhasil ditambahkan']);
        } else {
            return response()->json(['error' => 'Stok barang habis'], 422);
        }
    }

    /**
     * Reject a borrow request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function rejectBorrowRequest(Request $request, $id)
    {
        Borrow::rejectBorrowRequest($id);

        return response()->json(['message' => 'Data berhasil ditambahkan']);
    }
}
