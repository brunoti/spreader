<?php

namespace Indb\Spreader\Drivers\Apns\Events;

use ZendService\Apple\Apns\Message;
use Indb\Spreader\Drivers\Apns\Driver;
use ZendService\Apple\Apns\Response\Message as Response;

class MessageWasSent
{
    /**
     * @var Response
     */
    protected $response;

    /**
     * @var Message;
     */
    protected $message;

    /**
     * @var Driver
     */
    protected $driver;

    /**
     * @const array
     */
    protected static $statusMessages = [
        Response::RESULT_PROCESSING_ERROR     => 'Processing error',
        Response::RESULT_MISSING_TOKEN        => 'Missing device token',
        Response::RESULT_MISSING_TOPIC        => 'Missing topic',
        Response::RESULT_MISSING_PAYLOAD      => 'Missing payload',
        Response::RESULT_INVALID_TOKEN_SIZE   => 'Invalid token size',
        Response::RESULT_INVALID_TOPIC_SIZE   => 'Invalid topic size',
        Response::RESULT_INVALID_PAYLOAD_SIZE => 'Invalid payload size',
        Response::RESULT_INVALID_TOKEN        => 'Invalid token',
        Response::RESULT_UNKNOWN_ERROR        => 'Unknown Error',
    ];

    public function __construct(
        Response $response,
        Message $message,
        Driver $apns
    ) {
        $this->response = $response;
        $this->message = $message;
        $this->driver = $apns;
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
     * Getter for message
     *
     * @return Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Response status text
     *
     * @return string
     */
    public function getReponseStatusText()
    {
        return isset(self::$statusMessages[$this->response->getCode()])
            ? self::$statusMessages[$this->response->getCode()]
            : self::$statusMessages[255];
    }

    /**
     * Return if the push received an OK response
     *
     * @return bool
     */
    public function isOk()
    {
        return $this->response->getCode() === Response::RESULT_OK;
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
