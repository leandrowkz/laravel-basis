<?php

namespace Leandrowkz\Basis\Tests\App\Events;

use Leandrowkz\Basis\Tests\App\Models\Task;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class TaskUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;
    public $original;

    /**
     * Create a new event instance.
     *
     * @param Task $data
     * @param Task $original
     */
    public function __construct(Task $data, Task $original)
    {
        $this->data = $data->setRelations([]);
        $this->original = $original->setRelations([]);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
