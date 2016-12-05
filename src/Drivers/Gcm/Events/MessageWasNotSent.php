<?php

namespace Indb\Spreader\Drivers\Gcm\Events;

use ZendService\Google\Gcm\Message;
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

    public function __construct(
        Message $message,
        RuntimeException $exception
    ) {
        $this->exception = $exception;
        $this->message = $message;
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
}
