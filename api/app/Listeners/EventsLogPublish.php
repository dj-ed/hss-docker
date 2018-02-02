<?php

namespace App\Listeners;

use App\Events\LogPublish;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EventsLogPublish
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
     * @param  LogPublish  $event
     * @return void
     */
    public function handle(LogPublish $event)
    {
        //
    }
}
