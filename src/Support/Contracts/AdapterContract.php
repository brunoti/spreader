<?php

namespace Indb\Spreader\Support\Contracts;

use Indb\Spreader\Models\PushContract;

interface AdapterContract
{
    public function send(PushContract $push);
    public function getDefinedParameters();
    public function getDefaultParameters();
    public function getRequiredParameters();
}
