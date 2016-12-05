<?php

namespace Indb\Spreader\Models;

use Indb\Spreader\Models\Message;
use Indb\Spreader\Support\Adapter;
use Indb\Spreader\Collections\DeviceCollection;

class Push implements PushContract
{
    /**
     * @var Adapter
     */
    private $adapter;

    /**
     * @var DeviceCollection
     */
    private $devices;

    /**
     * @var Message
     */
    private $message;

    /**
     * Push model constructor
     *
     * @param Adapter $adapter
     * @param DeviceCollection $devices
     * @param Message $message
     */
    public function __construct(
        Adapter $adapter,
        DeviceCollection $devices,
        Message $message
    ) {
        $this->adapter = $adapter;
        $this->devices = $devices;
        $this->message = $message;
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
     * Getter for devices
     *
     * @return DeviceCollection
     */
    public function getDevices()
    {
        return $this->devices;
    }

    /**
     * Getter for adapter
     *
     * @return Adapter
     */
    public function getAdapter()
    {
        return $this->adapter;
    }
}
