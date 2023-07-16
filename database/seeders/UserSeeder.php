<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'user_code' => 'ADM' . Str::random(6),
            'email' => 'admin@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'role' => 'admin',
            'gender' => 'laki-laki',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);

        User::create([
            'username' => 'operator',
            'user_code' => 'OPT' . Str::random(6),
            'email' => 'operator@example.com',
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'role' => 'operator',
            'gender' => 'perempuan',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);

        User::create([
            'username' => 'borrower',
            'user_code' => 'BWR' . Str::random(6),
            'email' => 'arvinajif5@gmail.com',
            'first_name' => 'David',
            'last_name' => 'Johnson',
            'role' => 'borrower',
            'gender' => 'laki-laki',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);
        // User::create([
        //     // 'name' => 'Borrower',
        //     'username' => 'borrower',
        //     'user_code' => 'BWR' . Str::random(6),
        //     'email' => 'borrower@example.com',
        //     'first_name' => 'David',
        //     'last_name' => 'Johnson',
        //     'role' => 'borrower',
        //     'gender' => 'laki-laki',
        //     'email_verified_at' => now(),
        //     'password' => Hash::make('password'),
        //     'remember_token' => Str::random(10),
        // ]);
    }
}
