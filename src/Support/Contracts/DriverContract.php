<?php

namespace Indb\Spreader\Support\Contracts;

use Indb\Spreader\Models\PushContract;

interface DriverContract extends ParameterContract
{
    /**
     * Sends the push
     *
     * @param PushContract $push
     *
     * @return void
     */
    public function send(PushContract $push);
}
