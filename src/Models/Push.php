<?php

namespace Indb\Spreader\Models;

use Indb\Spreader\Models\Message;
use Indb\Spreader\Support\Driver;
use Indb\Spreader\Collections\DeviceCollection;

class Push implements PushContract
{
    /**
     * @var Driver
     */
    private $driver;

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
     * @param Driver $driver
     * @param DeviceCollection $devices
     * @param Message $message
     */
    public function __construct(
        Driver $driver,
        DeviceCollection $devices,
        Message $message
    ) {
        $this->driver = $driver;
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
     * Getter for driver
     *
     * @return Driver
     */
    public function getDriver()
    {
        return $this->driver;
    }
}
