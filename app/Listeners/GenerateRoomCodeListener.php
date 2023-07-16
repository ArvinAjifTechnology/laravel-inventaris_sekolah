<?php

namespace App\Listeners;

use App\Events\RoomCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GenerateRoomCodeListener
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
     * @param  RoomCreated  $event
     * @return void
     */
    public function handle(RoomCreated $event)
    {
        $room = $event->room;

        $randomString = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 9);
        $roomCode = 'RM' . $randomString . str_pad(($room->count() + 1), 9, '0', STR_PAD_LEFT);

        $room->room_code = $roomCode;
        $room->save();
    }
}
