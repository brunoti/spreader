<?php

namespace Indb\Spreader\Drivers\Apns\Jobs;

use Illuminate\Bus\Queueable;
use Indb\Spreader\Drivers\Apns\Driver;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Indb\Spreader\Drivers\Apns\Events\MessageWasSent;
use Indb\Spreader\Drivers\Apns\Events\MessageWasNotSent;

class QueuePush implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    /**
     * @var Driver
     */
    protected $driver;

    protected $envelopes;

    /**
     * Create a new job instance
     *
     * @param Driver $driver
     *
     * @return void
     */
    public function __construct(Driver $driver, array $envelopes)
    {
        $this->driver = $driver;
        $this->envelopes = $envelopes;
    }

    /**
     * Execute the job
     *
     * @return void
     */
    public function handle()
    {
        $client = $this->driver->getConnectedPushClient();

        foreach($this->envelopes as $envelope) {
            try {
                $response = $client->send($envelope);
            } catch (RuntimeException $error) {
                event(new MessageWasNotSent($error));
                continue;
            }

            event(new MessageWasSent($response));
        }
    }
}
