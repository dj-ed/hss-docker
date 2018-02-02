<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class LogEvents
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var object
     */
    public $object;

    /**
     * Create a new event instance.
     *
     * @param object $object
     *
     * @return void
     */
    public function __construct($object)
    {
        $this->object = $object;
    }
}
