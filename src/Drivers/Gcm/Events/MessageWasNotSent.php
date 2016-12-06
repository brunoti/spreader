<?php

namespace Indb\Spreader\Drivers\Gcm\Events;

use ZendService\Google\Gcm\Message;
use Indb\Spreader\Drivers\Gcm\Driver;
use ZendService\Google\Exception\RuntimeException;

class MessageWasNotSent
{
    /**
     * @var RuntimeException
     */
    protected $exception;

    /**
     * @var Message
     */
    protected $message;

    /**
     * @var Driver
     */
    protected $driver;

    public function __construct(
        Message $message,
        RuntimeException $exception,
        Driver $gcm
    ) {
        $this->exception = $exception;
        $this->message = $message;
        $this->driver = $gcm;
    }

    /**
     * Getter for throwed exception
     *
     * @return RuntimeException
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * Getter for message that was tried to be sent
     *
     * @return Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Getter for driver
     *
     * @return Driver
     */
    public function getDriver()
    {
        return $this->driver;
    }
}
