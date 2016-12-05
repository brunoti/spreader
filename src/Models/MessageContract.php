<?php
namespace Indb\Spreader\Models;

interface MessageContract
{
    public function getText();
    public function setText($text);
}
