<?php

namespace Indb\Spreader\Models;

use Indb\Spreader\Support\Parameters;

class Message implements MessageContract
{
    use Parameters;

    /**
     * @var string
     */
    private $text;

    /**
     * @param string $text
     */
    public function __construct($text, array $parameters = [])
    {
        $this->text = $text;
        $this->parameters = $parameters;
    }

    /**
     * Get text.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
    /**
     * Set text
     *
     * @param string $text Text
     *
     * @return MessageContract
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }
}
