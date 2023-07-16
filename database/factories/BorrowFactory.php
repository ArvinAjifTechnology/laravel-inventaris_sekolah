<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

use Faker\Generator as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Borrow>
 */
class BorrowFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Rentang waktu 6 bulan dari tanggal sekarang
        $createdAt = Carbon::parse($this->faker->dateTimeBetween('-6 months', 'now'));
        $updatedAt = $this->faker->dateTimeBetween($createdAt, 'now');

        // Menghasilkan tanggal peminjaman dan pengembalian secara acak dalam rentang waktu
        $borrowDate = $this->faker->dateTimeBetween($createdAt, $updatedAt);
        $returnDate = $this->faker->dateTimeBetween($borrowDate, $updatedAt);

        // Menghitung total rental price dan late fee berdasarkan selisih hari
        $rentalPrice = $this->faker->numberBetween(90000, 9000000);
        $borrowDays = Carbon::parse($borrowDate)->diffInDays(Carbon::parse($returnDate));
        $totalRentalPrice = $rentalPrice * $borrowDays;
        $lateFee = $totalRentalPrice * 0.1;

        return [
            'borrow_date' => $borrowDate,
            'return_date' => $returnDate,
            'item_id' => Item::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
            'borrow_quantity' => $this->faker->numberBetween(1, 10),
            'late_fee' => $lateFee,
            'total_rental_price' => $totalRentalPrice,
            'borrow_status' => $this->faker->randomElement(['borrowed', 'completed']),
            'sub_total' => $totalRentalPrice + $lateFee,
            'created_at' => $borrowDate,
            'updated_at' => $returnDate,
        ];
    }
}
