<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Gate::allows('admin')) {
            $items = Item::latest()->with(['rooms'])->get();
        } elseif (Gate::allows('operator')) {
            $items = Item::getAllForOperator();
        } else {
            abort(403, 'Unauthorized');
        }

        return response()->json([
            'success' => true,
            'message' => 'Data Ditemukan',
            'data' => $items
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_name' => ['required', 'string'],
            'room_id' => ['required', 'integer'],
            'description' => ['required', 'string'],
            'condition' => ['required', 'string'],
            'rental_price' => ['required', 'integer'],
            'late_fee_per_day' => ['required', 'integer'],
            'quantity' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Ada Kesalahan',
                'data' => $validator->errors()
            ], 400);
        }

        $item = Item::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'data berhasil ditambahkan',
            'data' => $item
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        $item = Item::where('id', $item->id)->first();

        return response()->json([
            'success' => true,
            'message' => 'Data Ditemukan',
            'data' => $item
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $validator = Validator::make($request->all(), [
            'item_name' => ['required', 'string'],
            'room_id' => ['required', 'integer'],
            'description' => ['required', 'string'],
            'condition' => ['required', 'string'],
            'rental_price' => ['required', 'integer'],
            'late_fee_per_day' => ['required', 'integer'],
            'quantity' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Ada Kesalahan',
                'data' => $validator->errors()
            ], 400);
        }

        $item = Item::find($item->id);
        $item->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'data berhasil diubah',
            'data' => $item
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item = Item::find($item->id);

        $item->borrows()->delete();

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'data berhasil dihapus',
            'data' => $item
        ], 200);
    }
}
