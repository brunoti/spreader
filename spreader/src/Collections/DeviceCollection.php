<?php

namespace Indb\Spreader\Collections;

use Indb\Spreader\Models\Device;
use Indb\Spreader\Support\Collection;

class DeviceCollection extends Collection
{
    /**
     * @param mixed $devices
     */
    public function __construct(array $devices = [])
    {
        $devices = is_array($devices) ? $devices : func_get_args();

        array_walk($devices, [$this, 'add']);
    }

    public function add(Device $device)
    {
        $this->push($device);
        return $this;
    }

    public function getTokens()
    {
        return array_map(function($device) {
            return $device->getToken();
        }, $this->items);
    }
}
