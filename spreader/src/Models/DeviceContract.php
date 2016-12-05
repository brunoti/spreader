<?php

namespace Indb\Spreader\Models;

interface DeviceContract
{
    public function getToken();
    public function setToken($token);
}
