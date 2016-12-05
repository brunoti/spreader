<?php

namespace Indb\Spreader\Models;

interface PushContract
{
    public function getDevices();
    public function getMessage();
    public function getDriver();
}
