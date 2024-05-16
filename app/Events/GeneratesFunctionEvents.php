<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Helper\NumberHelper;

class GeneratesFunctionEvents implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(protected string $message, protected int $currentRow, protected int $totalRows, protected User $user)
    {
        $this->percentage = NumberHelper::percentage($currentRow, $totalRows);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('generating-general-checks.' . $this->user->id),
        ];
    }

    public function broadcastAs()
    {
        return 'generating-reports';
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message,
            'percentage' => $this->percentage,
            'currentRow' => $this->currentRow,
            'totalRows' => $this->totalRows,
        ];
    }
}
