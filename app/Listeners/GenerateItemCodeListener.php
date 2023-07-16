<?php

namespace App\Listeners;

use App\Events\ItemCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GenerateItemCodeListener
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
     * @param  ItemCreated  $event
     * @return void
     */
    public function handle(ItemCreated $event)
    {
        $item = $event->item;

        $randomString = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
        $itemCode = 'ITM' . $randomString . str_pad(($item->count() + 1), 6, '0', STR_PAD_LEFT);

        $item->item_code = $itemCode;
        $item->save();
    }
}
