<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SampleEvent implements ShouldBroadcast
{
    public $progress;

    public function __construct(int $progress)
    {
        $this->progress = $progress;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('sample-progress');
    }
}
