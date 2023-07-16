<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
            $rooms = Room::latest()->with(['user'])->get(); //model
        } elseif (Gate::allows('operator')) {
            $rooms = Room::where('user_id', auth()->user()->id)->latest()->get();
        } else {
            abort(403, 'Unauthorized');
        }
        return response()->json([
            'data' => $rooms
        ], 200);
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
            return response()->json([
                'success' => false,
                'message' => 'Ada Kesalahan',
                'data' => $validator->errors()
            ], 400);
        }

        $room = Room::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'data berhasil ditambahkan',
            'data' => $room
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        $room = Room::where('id', $room->id)->first();

        return response()->json([
            'success' => true,
            'message' => 'data berhasil ditambahkan',
            'data' => $room
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        $validator = Validator::make($request->all(), [
            'room_name' => ['required', 'string', Rule::unique('rooms')->ignore($room->id)],
            'user_id' => ['required', 'integer'],
            'description' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Ada Kesalahan',
                'data' => $validator->errors()
            ], 400);
        }

        $room = Room::find($room->id);

        $room->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'data berhasil ditambahkan',
            'data' => $room
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        $room = Room::find($room->id);

        if ($room) {
            $room->items()->delete();
            $room->user()->delete();
            $room->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'data berhasil dihapus',
            'data' => $room
        ], 200);
    }
}
