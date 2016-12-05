<?php

namespace Indb\Spreader\Models;

use Indb\Spreader\Support\Parameters;

class Device implements DeviceContract
{
    use Parameters;

    /**
     * @var string
     */
    private $token;

    /**
     * @param string $token
     */
    public function __construct($token, array $parameters = [])
    {
        $this->token = $token;
        $this->parameters = $parameters;
    }

    /**
     * Get token.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }
    /**
     * Set token.
     *
     * @param string $token Token
     *
     * @return DeviceContract
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }
}
