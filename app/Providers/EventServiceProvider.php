<?php

namespace App\Providers;

use App\Events\BorrowCreated;
use App\Events\ItemCreated;
use App\Events\RoomCreated;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\UserCreated;
use App\Listeners\GenerateBorrowCodeListener;
use App\Listeners\GenerateItemCodeListener;
use App\Listeners\GenerateUserCodeListener;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UserCreated::class => [
            GenerateUserCodeListener::class,
        ],
        RoomCreated::class => [
            GenerateRoomCodeListener::class,
        ],
        ItemCreated::class => [
            GenerateItemCodeListener::class,
        ],
        BorrowCreated::class => [
            GenerateBorrowCodeListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        $this->listen[UserCreated::class] = [
            GenerateUserCodeListener::class,
        ];

        $this->listen[RoomCreated::class] = [
            GenerateRoomCodeListener::class,
        ];

        $this->listen[ItemCreated::class] = [
            GenerateItemCodeListener::class,
        ];

        $this->listen[BorrowCreated::class] = [
            GenerateBorrowCodeListener::class,
        ];
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
