<?php
namespace Indb\Spreader\Models;

interface MessageInterface
{
    public function getText();
    public function setText($text);
}
