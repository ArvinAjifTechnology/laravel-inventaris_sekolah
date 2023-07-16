<?php

namespace App\Rules;

use Closure;
use App\Models\Item;
use Illuminate\Contracts\Validation\Rule;

class SufficientQuantityRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */

    public $availableQuantity;

    public function passes($attribute, $value)
    {
        // Mengambil ID item dari permintaan
        $itemId = request()->input('item_id');

        // Mengambil jumlah item yang tersedia dari database
        $availableQuantity = Item::where('id', $itemId)->value('quantity');

        // Menyimpan jumlah item yang tersedia ke dalam properti
        $this->availableQuantity = $availableQuantity;

        // Memeriksa apakah jumlah peminjaman melebihi jumlah yang tersedia
        return $value <= $availableQuantity;
    }

    public function message()
    {
        return 'Jumlah peminjaman melebihi jumlah yang tersedia:  ' .  $this->availableQuantity;
    }
}
