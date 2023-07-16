<?php

namespace App\Listeners;

use App\Events\BorrowCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GenerateBorrowCodeListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param BorrowCreated
     * @return void
     */
    public function handle(BorrowCreated $event)
    {
        $borrow = $event->borrow;

        $randomString = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
        $borrowCode = 'BRW' . $randomString . str_pad(($borrow->count() + 1), 6, '0', STR_PAD_LEFT);

        $borrow->borrow_code = $borrowCode;
        $borrow->save();
    }
}
