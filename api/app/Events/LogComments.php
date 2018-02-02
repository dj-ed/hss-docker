<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class LogComments
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var int
     */
    public $modelId;

    /**
     * @var string
     */
    public $modelType;

    /**
     * @var object
     */
    public $object;

    /**
     * @var object
     */
    public $user;

    /**
     * @var string
     */
    public $relationModel;

    /**
     * Create a new event instance.
     *
     * @param integer $modelId
     * @param string $modelType
     * @param object $object
     * @param object $user
     * @param string $relationModel
     *
     * @return void
     */
    public function __construct($modelId, $modelType, $object, $user, $relationModel)
    {
        $this->modelId = $modelId;
        $this->modelType = $modelType;
        $this->object = $object;
        $this->user = $user;
        $this->relationModel = $relationModel;
    }
}
