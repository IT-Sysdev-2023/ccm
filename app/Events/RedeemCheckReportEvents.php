<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Helper\NumberHelper;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class RedeemCheckReportEvents implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

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
            new PrivateChannel('generating-redeem-reports.' . $this->user->id),
        ];
    }

    public function broadcastAs()
    {
        return 'generating-redeem-reports';
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
