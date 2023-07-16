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
                'rental_price' => $faker->randomFloat(2, 10, 100),
                'late_fee_per_day' => $faker->randomFloat(2, 1, 10),
                'quantity' => $faker->numberBetween(1, 99999),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
