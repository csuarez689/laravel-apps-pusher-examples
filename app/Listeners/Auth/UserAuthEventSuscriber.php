<?php

namespace App\Listeners\Auth;

use App\Events\Auth\UserSessionChanged;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserAuthEventSuscriber
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle user login events.
     */
    public function handleUserLogin($event)
    {
        broadcast(new UserSessionChanged("{$event->user->name} is online", 'success'))->toOthers();
    }

    /**
     * Handle user logout events.
     */
    public function handleUserLogout($event)
    {
        broadcast(new UserSessionChanged("{$event->user->name} is offline", 'warning'))->toOthers();
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return array
     */
    public function subscribe($events)
    {
        return [
            Login::class => 'handleUserLogin',
            Logout::class => 'handleUserLogout',
        ];
    }
}
