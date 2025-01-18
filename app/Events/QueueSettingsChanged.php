<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QueueSettingsChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $queueCode; // Dynamic queue name

    /**
     * Create a new event instance.
     *
     * @param string $queueCode
     */
    public function __construct($queueCode)
    {
        $this->queueCode = $queueCode;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    //Broadcast an event on a channel
    public function broadcastOn()
    {
        return [new Channel('live-queue.'.$this->queueCode)];
    }

    public function broadcastWith()
    {
        return [
        ];
    }
}
