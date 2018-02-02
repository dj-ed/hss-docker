<?php

namespace App\Listeners;

use App\Events\LogTags;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EventsLogTags
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
     * @param  LogTags  $event
     * @return void
     */
    public function handle(LogTags $event)
    {
        //
    }
}
