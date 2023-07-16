<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Gate::allows('admin')) {
            $items = Item::latest()->with(['room'])->get();
        } elseif (Gate::allows('operator')) {
            $items = Item::getAllForOperator();
        } else {
            abort(403, 'Unauthorized');
        }

        return view('items.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Gate::allows('admin')) {
            $rooms = Room::all();
        } elseif (Gate::allows('operator')) {
            $rooms = Room::where('user_id', Auth::id())->latest()->get();
        } else {
            abort(403, 'Unauthorized');
        }

        return view('items.create', compact('rooms'));
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
            return redirect(route('admin.items.create'))->withErrors($validator)->withInput();
        }

        Item::create($request->all());

        return redirect(route('admin.items.index'))->with('status', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = Item::findOrFail($id);

        return view('items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = Item::findOrFail($id);
        $rooms = Gate::allows('admin') ? Room::getAll() : Room::where('user_id', Auth::id())->latest()->get();

        return view('items.edit', compact('item', 'rooms', 'id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = Item::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'item_name' => ['required', 'string'],
            'item_code' => ['required', 'string', Rule::unique('items')->ignore($item->id)],
            'room_id' => ['required', 'integer'],
            'description' => ['required', 'string'],
            'condition' => ['required', 'string'],
            'rental_price' => ['required', 'integer'],
            'late_fee_per_day' => ['required', 'integer'],
            'quantity' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.items.edit', $id))->withErrors($validator)->withInput();
        }

        $item->update($request->all());

        return redirect(route('admin.items.index'))->with('status', 'Data berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Item::findOrFail($id);
        $item->borrows()->delete();
        $item->delete();

        return redirect(route('admin.items.index'))->with('status', 'Data berhasil dihapus');
    }
}
