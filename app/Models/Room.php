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

    /**
     * Mendapatkan semua data ruangan.
     */
    public static function getAll()
    {
        return self::query()
            ->join('users', 'rooms.user_id', '=', 'users.id')
            ->select('rooms.*', DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS user_name"))
            ->get();
    }

    /**
     * Mendapatkan semua data ruangan untuk operator saat ini.
     */
    public static function getAllForOperator()
    {
        $currentUserId = Auth::id();

        return self::query()
            ->join('users', 'rooms.user_id', '=', 'users.id')
            ->where('users.id', $currentUserId)
            ->select('rooms.*', DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS user_name"))
            ->get();
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
