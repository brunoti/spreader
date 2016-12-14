<?php

namespace Indb\Spreader\Drivers\Gcm\Jobs;

use Exception;
use Indb\Spreader\Drivers\Gcm\Driver;
use Indb\Spreader\Drivers\Gcm\Events\MessageWasSent;
use Indb\Spreader\Drivers\Gcm\Events\MessageWasNotSent;

class QueuePush
{
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

        try {
            $response = $client->send($this->message);
        } catch (Exception $error) {
            event(new MessageWasNotSent($this->message, $error, $this->driver));
        }

        event(new MessageWasSent($response, $message, $this->driver));
    }
}
