<?php

namespace Indb\Spreader\Drivers\Apns\Events;

use ZendService\Apple\Apns\Message;
use ZendService\Apple\Apns\Exception\RuntimeException;


class MessageWasNotSent
{
    public function __construct(
        Message $message,
        ResponseException $exception
    ) {
        $this->message = $message;
        $this->exception = $exception;
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
