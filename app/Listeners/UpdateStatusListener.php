<?php

namespace App\Listeners;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Auth;
use App\Events\OnlineEvents;
use App\Events\OnlineUserEvents;
class UpdateStatusListener
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
     */
    public function handle(object $event): void
    {
        //
        $user = $event->user;
        $event->user->is_online = $event instanceof Login;
        // dd(1);
        $event->user->save();

        // OnlineEvents::dispatch()
        // dd(1);
        OnlineUserEvents::dispatch($user);
        // GeneratesFunctionEvents::dispatch(Auth::user());

    }
}
