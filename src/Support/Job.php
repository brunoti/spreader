<?php

namespace Users\bruno\Sites\Indb\cscity\spreader\src\Support;

use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class Job implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    /**
     * Handle a job failure.
     *
     * @return void
     */
    public function failed()
    {
        Log::info(
            'Push job "' . static::class . '" failed at: ' . Carbon::now()->toDateTimeString() . '.'
        );
    }
}
