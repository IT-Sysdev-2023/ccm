<?php

namespace App\Events;

use App\Helper\NumberHelper;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExcelGenerateEvents implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $percentage; 

    /**
     * Create a new event instance.
     */
    public function __construct(protected string $department, protected string $message, protected int $currentRow, protected int $totalRows, protected User $user, protected int $preprocess, protected int $departmenToBeProcessed)
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
        // dd($this->user->id);
        return [
            new PrivateChannel('excel-progress.' . $this->user->id),
        ];
    }


    public function broadcastAs()
    {
        return 'generate-excel';
    }

    public function broadcastWith()
    {
        return [
            'department' => $this->department,
            'message' => $this->message,
            'percentage' => $this->percentage,
            'currentRow' => $this->currentRow,
            'totalRows' => $this->totalRows,
            'preProcess' => $this->preprocess,
            'departmentToBeProcessed' => $this->departmenToBeProcessed,
        ];
    }
}
