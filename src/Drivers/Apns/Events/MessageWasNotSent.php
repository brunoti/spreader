<?php

namespace Indb\Spreader\Drivers\Apns\Events;

use ZendService\Apple\Apns\Message;
use Indb\Spreader\Drivers\Apns\Driver;


class MessageWasNotSent
{

    /**
     * @var Message
     */
    protected $message;

    /**
     * @var ResponseException
     */
    protected $exception;

    /**
     * @var Driver
     */
    protected $driver;


    public function __construct(
        Message $message,
        ResponseException $exception,
        Driver $apns
    ) {
        $this->message = $message;
        $this->exception = $exception;
        $this->driver = $apns;
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
     * Get the driver feedback
     *
     * @return array
     */
    public function getFeedback()
    {
        return $this->driver->getFeedback();
    }

    /**
     * Getter for Driver
     *
     * @return Driver
     */
    public function getDriver()
    {
        return $this->driver;
    }

}
