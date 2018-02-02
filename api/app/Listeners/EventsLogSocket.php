<?php

namespace App\Listeners;

use App\Events\LogSocket;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EventsLogSocket
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
     * Handle the event.
     *
     * @param  LogSocket  $event
     * @return void
     */
    public function handle(LogSocket $event)
    {
        //
    }
}
