<?php

namespace App\Models;

use App\Events\ItemCreated;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $casts = [
        'rental_price' => 'float',
    ];

    protected $dispatchesEvents = [
        'created' => ItemCreated::class,
    ];

    public static function getAllForOperator()
    {
        return Item::whereHas('room', function ($query) {
            $query->where('user_id', Auth::id());
        })->get();
    }


    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }
}
