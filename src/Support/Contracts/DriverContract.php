<?php

namespace Indb\Spreader\Support\Contracts;

use Indb\Spreader\Models\PushContract;

interface DriverContract extends ParameterContract
{
    public function send($push);
}
