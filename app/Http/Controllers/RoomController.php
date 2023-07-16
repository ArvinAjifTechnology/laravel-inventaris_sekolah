<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $rooms= DB::select('select * from rooms');
        if (Gate::allows('admin')) {
            // akses yang diizinkan untuk admin
            $rooms = Room::getAll(); //model
            $rooms = collect($rooms);
        } elseif (Gate::allows('operator')) {
            $rooms = Room::getAllForOperator();
            $rooms = collect($rooms);
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
            // akses yang diizinkan untuk admin
            $users = User::latest()->where('role', '=', 'operator')->get();
        } else {
            abort(403, 'Unauthorized');
        }
        // $user = Collection::make($user);
        return view('rooms.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_name' => ['required', 'string:50', Rule::unique('rooms')],
            // 'room_code' => ['required', 'string', Rule::unique('rooms')],
            'user_id' => ['required', 'integer'],
            'description' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect('/admin/rooms/create')
                ->withErrors($validator)
                ->withInput();
        }

        Room::insert($request);

        return redirect('/admin/rooms')->withErrors($validator)->withInput()->with('status', 'Selamat Data Berhasil Di Tambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (Gate::allows('admin')) {
            // akses yang diizinkan untuk admin
            $room = Room::find($id); //model
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
        $room = Room::findId($id);
        $users = User::latest()->where('role', 'operator')->get();
        return view('rooms.edit', compact('room', 'id', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $id = $request->input('id');
        $room = DB::select('select * from rooms where id = ?', [$id]);
        $validator = Validator::make($request->all(), [
            'room_name' => ['required', 'string', Rule::unique('rooms')->ignore($id)],
            'user_id' => ['required', 'integer'],
            'description' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return redirect('/admin/rooms/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        }
        $fullName = $request->input('first_name') . $request->input('last_name');
        // Mengupdate User
        Room::edit($request);
        return redirect('admin/rooms')->withErrors($validator)->with('status', 'Selamat Data Berhasil Di Update')->withInput();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $room = DB::selectOne('SELECT * FROM rooms WHERE id = ?', [$id]);

        if ($room) {
            $roomItems = DB::delete('DELETE FROM items WHERE room_id = ?', [$room->id]);
            $roomUser = DB::delete('DELETE FROM users WHERE id = ?', [$room->user_id]);

            DB::delete('DELETE FROM rooms WHERE id = ?', [$id]);
        }


        if (Gate::allows('admin')) {
            return redirect('/admin/rooms')->with('status', 'Data berhasil Di Hapus');
        } elseif (Gate::allows('operator')) {
            return redirect('/oprator/rooms')->with('status', 'Data berhasil Di Hapus');
        } else {
            abort(403, 'Unauthorized');
        }
    }
}
