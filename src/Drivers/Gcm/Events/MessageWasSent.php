<?php

namespace Indb\Spreader\Drivers\Gcm\Events;

use ZendService\Google\Gcm\Message;
use ZendService\Google\Gcm\Response;
use Indb\Spreader\Drivers\Gcm\Driver;

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

    /**
     * @var Driver
     */
    protected $driver;

    public function __construct(
        Response $response,
        Message $message,
        Driver $driver
    ) {
        $this->response = $response;
        $this->message = $message;
        $this->driver = $driver;
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
