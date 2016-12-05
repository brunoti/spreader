<?php

namespace Indb\Spreader\Support;

trait Parameters
{
    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * Get parameters
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Has parameter
     *
     * @param string $key Key
     *
     * @return boolean
     */
    public function hasParameter($key)
    {
        return array_key_exists($key, $this->parameters);
    }

    /**
     * Get parameter
     *
     * @param string $key     Key
     * @param mixed  $default Default
     *
     * @return mixed
     */
    public function getParameter($key, $default = null)
    {
        return $this->hasParameter($key) ? $this->parameters[$key] : $default;
    }

    /**
     * Set parameters
     *
     * @param array $parameters Parameters
     *
     * @return self
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Set parameter
     *
     * @param string $key   Key
     * @param mixed  $value Value
     *
     * @return mixed
     */
    public function setParameter($key, $value)
    {
        $this->parameters[$key] = $value;

        return $value;
    }
}
