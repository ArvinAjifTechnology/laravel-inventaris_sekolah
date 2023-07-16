<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Gate::allows('admin')) {
            $items = Item::getAll();
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
            $rooms = Room::latest()->where('user_id', '=', Auth::user()->id)->get();
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


        if (Gate::allows('admin')) {
            if ($validator->fails()) {
                return redirect('/admin/items/create')
                    ->withErrors($validator)
                    ->withInput();
            }

            Item::insert($request);

            return redirect('/admin/items')->withErrors($validator)->withInput()->with('status', 'Selamat Data Berhasil Di Tambahkan');
        } elseif (Gate::allows('operator')) {
            if ($validator->fails()) {
                return redirect('/operator/items/create')
                    ->withErrors($validator)
                    ->withInput();
            }
            Item::insert($request);

            return redirect('/operator/items')->withErrors($validator)->withInput()->with('status', 'Selamat Data Berhasil Di Tambahkan');
        } else {
            abort(403, 'Unauthorized');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = Item::find($id);

        return view('items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (Gate::allows('admin')) {
            $item = Item::findId($id);
            $rooms = Room::getAll();
        } elseif (Gate::allows('operator')) {
            $item = Item::findId($id);
            $rooms = Room::latest()->where('user_id', '=', Auth::user()->id)->get();
        } else {
            abort(403, 'Unauthorized');
        }
        return view('items.edit', compact('item', 'rooms', 'id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $id = $request->input('id');
        $item = DB::select('select * from items where id = ?', [$id]);
        $validator = Validator::make($request->all(), [
            'item_name' => ['required', 'string'],
            'item_code' => ['required', 'string', Rule::unique('items')->ignore($item[0]->id)],
            'room_id' => ['required', 'integer'],
            'description' => ['required', 'string'],
            'condition' => ['required', 'string'],
            'rental_price' => ['required', 'integer'],
            'late_fee_per_day' => ['required', 'integer'],
            'quantity' => ['required', 'integer'],
        ]);

        if (Gate::allows('admin')) {
            if ($validator->fails()) {
                return redirect('/admin/items/' . $id . 'edit')
                    ->withErrors($validator)
                    ->withInput();
            }

            Item::edit($request);

            return redirect('/admin/items')->withErrors($validator)->with('status', 'Selamat Data Berhasil Di Update')->withInput();
        } elseif (Gate::allows('operator')) {
            if ($validator->fails()) {
                return redirect('/operator/items/' . $id . '/edit')
                    ->withErrors($validator)
                    ->withInput();
            }

            Item::edit($request);

            return redirect('/operator/items')->withErrors($validator)->with('status', 'Selamat Data Berhasil Di Update')->withInput();
        } else {
            abort(403, 'Unauthorized');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Item::where('id', '=', $id)->first();
        $item->borrows()->delete();

        Item::destroy($id);

        if (Gate::allows('admin')) {
            return redirect('/admin/items')->with('status', 'Data berhasil Di Hapus');
        } elseif (Gate::allows('operator')) {
            return redirect('/oprator/items')->with('status', 'Data berhasil Di Hapus');
        } else {
            abort(403, 'Unauthorized');
        }
    }
}
