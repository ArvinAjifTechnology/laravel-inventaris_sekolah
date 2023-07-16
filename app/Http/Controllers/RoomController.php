<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Gate::allows('admin')) {
            $rooms = Room::getAll();
        } elseif (Gate::allows('operator')) {
            $rooms = Room::getAllForOperator();
        } else {
            abort(403, 'Unauthorized');
        }

        return view('rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Gate::allows('admin')) {
            $users = User::where('role', 'operator')->get();
        } else {
            abort(403, 'Unauthorized');
        }

        return view('rooms.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_name' => ['required', 'string', 'max:50', 'unique:rooms'],
            'user_id' => ['required', 'integer'],
            'description' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect('/admin/rooms/create')
                ->withErrors($validator)
                ->withInput();
        }

        Room::create($request->all());

        return redirect('/admin/rooms')->with('status', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (Gate::allows('admin')) {
            $room = Room::find($id);
        } elseif (Gate::allows('operator')) {
            $room = Room::find($id);
        } else {
            abort(403, 'Unauthorized');
        }

        return view('rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $room = Room::find($id);
        $users = User::where('role', 'operator')->get();

        return view('rooms.edit', compact('room', 'id', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $room = Room::find($id);

        $validator = Validator::make($request->all(), [
            'room_name' => ['required', 'string', 'max:50', 'unique:rooms,room_name,' . $id],
            'user_id' => ['required', 'integer'],
            'description' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect('/admin/rooms/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        }

        $room->update($request->all());

        return redirect('/admin/rooms')->with('status', 'Data berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $room = Room::find($id);

        if ($room) {
            $room->items()->delete();
            $room->delete();
        }

        if (Gate::allows('admin')) {
            return redirect('/admin/rooms')->with('status', 'Data berhasil dihapus');
        } elseif (Gate::allows('operator')) {
            return redirect('/oprator/rooms')->with('status', 'Data berhasil dihapus');
        } else {
            abort(403, 'Unauthorized');
        }
    }
}
