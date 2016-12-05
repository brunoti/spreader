<?php

namespace Indb\Spreader\Collections;

use Indb\Spreader\Models\Device;
use Indb\Spreader\Support\Collection;

class DeviceCollection extends Collection
{
    public function add(Device $device)
    {
        $this->push($device);
        return $this;
    }
}
