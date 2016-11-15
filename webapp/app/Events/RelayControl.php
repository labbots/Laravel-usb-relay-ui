<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RelayControl extends Event
{
    use SerializesModels;

    public $onState;
    public $offState;
    public $delay;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($onState = null,$offState = null,$delay = null)
    {
        $this->onState = $onState;
        $this->offState = $offState;
        $this->delay = $delay;

    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
