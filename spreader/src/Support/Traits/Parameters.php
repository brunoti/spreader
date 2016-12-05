<?php

namespace Indb\Spreader\Support\Traits;

use Symfony\Component\OptionsResolver\OptionsResolver;

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


    /**
     * Resolve the paramaters using the OptionResolver component
     *
     * @param array $parameters - The paraeters
     *
     * @return void;
     */
    protected function start(array $parameters)
    {
        $resolver = new OptionsResolver();
        $resolver->setDefined($this->getDefinedParameters());
        $resolver->setDefaults($this->getDefaultParameters());
        $resolver->setRequired($this->getRequiredParameters());
        $this->parameters = $resolver->resolve($parameters);
    }
}
