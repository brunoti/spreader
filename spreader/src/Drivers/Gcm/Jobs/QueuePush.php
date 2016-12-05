<?php

namespace Indb\Spreader\Drivers\Gcm\Jobs;

use Illuminate\Bus\Queueable;
use Indb\Spreader\Drivers\Gcm\Driver;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Indb\Spreader\Drivers\Gcm\Events\MessageWasSent;
use Indb\Spreader\Drivers\Gcm\Events\MessageWasNotSent;

class QueuePush implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    /**
     * @var Driver
     */
    protected $driver;

    protected $message;

    /**
     * Create a new job instance
     *
     * @param Driver $driver
     *
     * @return void
     */
    public function __construct(Driver $driver, $message)
    {
        $this->driver = $driver;
        $this->message = $message;
    }

    /**
     * Execute the job
     *
     * @return void
     */
    public function handle()
    {
        $client = $this->driver->getOpenedClient();
        event(new MessageWasSent($client->send($this->message)));
    }
}
