<?php

namespace Indb\Spreader\Drivers\Apns\Events;

use ZendService\Apple\Apns\Message;
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
}
