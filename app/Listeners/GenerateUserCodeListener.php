<?php

namespace App\Listeners;

use App\Events\UserCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GenerateUserCodeListener
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
     * @param  UserCreated  $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        $user = $event->user;
        $role = $user->role;

        $rolePrefix = '';

        if ($role === 'admin') {
            $rolePrefix = 'ADM';
        } elseif ($role === 'operator') {
            $rolePrefix = 'OPT';
        } elseif ($role === 'borrower') {
            $rolePrefix = 'BWR';
        }

        $randomString = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 9);
        $userCode = $rolePrefix . $randomString . str_pad(($user->where('role', $role)->count() + 1), 9, '0', STR_PAD_LEFT);

        $user->user_code = $userCode;
        $user->save();
    }
}
