<?php

namespace Indb\Spreader\Collections;

use Indb\Spreader\Models\Device;
use Illuminate\Support\Collection;

class DeviceCollection extends Collection
{
    /**
     * DeviceCollection constructor
     *
     * @param Array<Device> $devices
     */
    public function __construct(array $devices = [])
    {
        $devices = is_array($devices) ? $devices : func_get_args();

        array_walk($devices, [$this, 'add']);
    }

    /**
     * Adds a device to the collection
     *
     * @param Device $device
     *
     * @return DeviceCollection
     */
    public function add(Device $device)
    {
        $this->push($device);
        return $this;
    }

    /**
     * Return an array of unique tokens
     *
     * @return Collection
     */
    public function getTokens()
    {
        return $this->map(function(Device $device) {
            return $device->getToken();
        })->unique();
    }
}
