<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\LogPublish'=>[
            'App\Listeners\EventsLogPublish'
        ],
        'App\Events\LogTags'=>[
            'App\Listeners\EventsLogTags'
        ],
        'App\Events\LogLikes'=>[
            'App\Listeners\EventsLogLikes'
        ],
        'App\Events\LogEvents'=>[
            'App\Listeners\EventsLogEvents'
        ],
        'App\Events\LogComments'=>[
            'App\Listeners\EventsLogComments'
        ],
        'App\Events\LogSocket'=>[
            'App\Listeners\EventsLogSocket'
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
