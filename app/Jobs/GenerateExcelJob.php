<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateExcelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //

        $totalSteps = 10;

        for ($step = 0; $step < $totalSteps; $step++) {
            // Perform a part of the Excel generation
            // ...

            // Calculate the progress percentage
            $progress = ($step + 1) / $totalSteps * 100;

            // Broadcast the progress update event
            event(new ProgressUpdated($progress));
        }

        // Finalize the Excel file generation
        // ...

        // Broadcast the job completion event
        event(new ProgressUpdated(100, true));
    }
}
