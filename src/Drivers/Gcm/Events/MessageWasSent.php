<?php

namespace Indb\Spreader\Drivers\Gcm\Events;

use ZendService\Google\Gcm\Message;
use ZendService\Google\Gcm\Response;

class MessageWasSent
{
    /**
     * @var Response
     */
    protected $response;

    /**
     * @var Message
     */
    protected $message;

    public function __construct(Response $response, Message $message)
    {
        $this->response = $response;
        $this->message = $message;
    }

    /**
     * Getter for received response
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Getter for sent message
     *
     * @return Message
     */
    public function getMessage()
    {
        return $this->message;
    }
}
