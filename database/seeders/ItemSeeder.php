<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            DB::table('items')->insert([
                // 'item_code' => 'ITM' . $faker->unique()->numberBetween(1000, 9999),
                'item_name' => $faker->word,
                'room_id' => $faker->numberBetween(1, 10),
                // 'room_id' => $faker->numberBetween(1, 5), // Replace with your room IDs
                'description' => $faker->sentence,
                'condition' => $faker->randomElement(['good', 'fair', 'bad']),
                'rental_price' => $faker->numberBetween(100000, 1000000), // Random rental price between 1,000 and 1,000,000
                'late_fee_per_day' => $faker->numberBetween(10000, 1000000),
                'quantity' => $faker->numberBetween(1, 99999),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
