<?php

namespace Indb\Spreader\Collections;

use Indb\Spreader\Models\Device;
use Illuminate\Support\Collection;

class DeviceCollection extends Collection
{
    /**
     * Return an array of unique tokens
     *
     * @return Collection
     */
    public function getTokens()
    {
        return collect($this->map(function(Device $device) {
            return $device->getToken();
        })->unique()->toArray());
    }
}
