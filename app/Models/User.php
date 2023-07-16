<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Events\UserCreated;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'full_name',
        'user_code',
        'username',
        'email',
        'role',
        'gender',
        'password',
        'fcm_token',
        'user_code',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'fcm_token',
    ];

    protected $dispatchesEvents = [
        'created' => UserCreated::class,
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Fungsi CRUD
     */

    public static function getAll()
    {
        $users = DB::select("SELECT * FROM users ORDER BY created_at DESC");

        return $users;
    }

    public static function insert($request)
    {
        DB::insert('INSERT INTO users (username,email,first_name, last_name,role, gender, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', [
            $request->input('username'),
            $request->input('email'),
            $request->input('first_name'),
            $request->input('last_name'),
            $request->input('role'),
            $request->input('gender'),
            bcrypt($request->input('email'))
        ]);
    }

    public static function edit($request, $id)
    {
        DB::update('UPDATE users SET username = ?, email = ?, first_name = ?, last_name = ?, role = ?, gender = ?, password = ? WHERE id = ?', [
            $request->input('username'),
            $request->input('email'),
            $request->input('first_name'),
            $request->input('last_name'),
            $request->input('role'),
            $request->input('gender'),
            bcrypt($request->input('email')),
            $id
        ]);
    }

    public static function destroy($username)
    {
        DB::delete('DELETE FROM users WHERE username = ?', [$username]);
    }

    /**
     * Fungsi Ini Digunakan Untuk Mengambil FullName User
     */

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }
}
