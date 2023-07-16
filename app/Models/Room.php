<?php

namespace App\Models;

use App\Events\RoomCreated;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class Room extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $dispatchesEvents = [
        'created' => RoomCreated::class,
    ];

    public static function getAll()
    {
        $query = "SELECT rooms.*, CONCAT(users.first_name, ' ', users.last_name) AS user_name
          FROM rooms
          INNER JOIN users ON rooms.user_id = users.id";

        $result = DB::select($query);

        // Mengonversi hasil ke dalam bentuk koleksi objek jika diperlukan
        // $rooms = collect($result);
        return $result;
    }

    public static function getAllForOperator()
    {
        $query = "SELECT rooms.*, CONCAT(users.first_name, ' ', users.last_name) AS user_name
        FROM rooms
        INNER JOIN users ON rooms.user_id = users.id
        WHERE users.id = " . Auth::user()->id;

        $result = DB::select($query);

        // Mengonversi hasil ke dalam bentuk koleksi objek jika diperlukan
        $rooms = collect($result);

        // Output atau penggunaan data rooms...;
        return $rooms;
    }

    public static function findId($id)
    {
        return DB::select('select * from rooms where id = ?', [$id]);
    }

    public static function insert($request)
    {
        DB::insert('INSERT INTO rooms (room_name,user_id,description) VALUES ( ?, ?, ?)', [
            $request->input('room_name'),
            $request->input('user_id'),
            $request->input('description')
        ]);
    }

    public static function edit($request)
    {
        DB::update('UPDATE rooms SET room_name = ? ,user_id = ? ,description = ? WHERE id = ?', [
            $request->input('room_name'),
            $request->input('user_id'),
            $request->input('description'),
            $request->input('id')
        ]);
    }

    public static function destroy($id)
    {
        DB::delete('DELETE FROM rooms WHERE id = ?', [$id]);
    }

    /**
     * Relasi Yang Digunakan
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
