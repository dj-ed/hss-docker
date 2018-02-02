<?php

namespace App\Listeners;

use App\Events\LogEvents;
use App\Models\Event;
use App\Models\EventsLog;

class EventsLogEvents
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
     * @param  LogEvents $event
     * @return void
     */
    public function handle(LogEvents $event)
    {
        EventsLog::create([
            'model_id' => $event->object->id,
            'model_type' => Event::class,
            'relation_model_id' => $event->object->model_id,
            'relation_model_type' => $event->object->model_type,
            'user_id' => $event->object->user_id,
            'from_user_id' => null,
            'description' => 'game estimated to start at ' . date('h:i A', strtotime($event->object->game->date_time)),
            'url_image' => '/img/sports/' . strtolower($event->object->sport->title) . '.svg',
            'status' => EventsLog::STATUS_NOT_READ
        ]);
    }
}
