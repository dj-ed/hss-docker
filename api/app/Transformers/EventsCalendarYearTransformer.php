<?php

namespace App\Transformers;

use App\Models\Event;
use League\Fractal\TransformerAbstract;

class EventsCalendarYearTransformer extends TransformerAbstract
{
    public function transform($event)
    {
        return [
            'id' => $event->id,
            'date' => $event->date,
            'gameType' => Event::getShortGameType($event->district),
            'sportId'=>$event->sport_id,
            'count'=>$event->count
        ];
    }
}