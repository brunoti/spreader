<?php
namespace Indb\Spreader\Support;

use ReflectionClass;

use Indb\Spreader\Spreader;
use Indb\Spreader\Support\Contracts\AdapterContract;

abstract class Adapter implements AdapterContract
{
    use Parameters;

    /**
     * @var string */ protected $environment;

    /** * @var string
     */
    protected $adapterName;

    /**
     * Constructor
     *
     * @param array $parameters Adapter specific parameters
     */
    public function __construct(array $parameters = [])
    {
        $resolver = new OptionsResolver();
        $resolver->setDefined($this->getDefinedParameters());
        $resolver->setDefaults($this->getDefaultParameters());
        $resolver->setRequired($this->getRequiredParameters());

        $reflectedClass    = new ReflectionClass($this);
        $this->adapterName = lcfirst($reflectedClass->getShortName());
        $this->parameters  = $resolver->resolve($parameters);
    }


    /**
     * Setter for the adapter name
     *
     * @param string $adapterName
     *
     * @return self
     */
    public function setAdapterName($adapterName)
    {
        $this->adapterName = $adapterName;
        return $this;
    }


    /**
     * Getter for the adapterName
     *
     * @return string
     */
    public function getAdapterName()
    {
        return $this->adapterName;
    }


    /**
     * Get Environment
     *
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Set Environment
     *
     * @param string $environment Environment value to set
     *
     * @return self
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
        return $this;
    }

    /**
     * isDevelopmentEnvironment
     *
     * @return boolean
     */
    public function isDevelopmentEnvironment()
    {
        return (Spreader::ENVIRONMENT_DEV === $this->getEnvironment());
    }

    /**
     * isProductionEnvironment
     *
     * @return boolean
     */
    public function isProductionEnvironment()
    {
        return (Spreader::ENVIRONMENT_PROD === $this->getEnvironment());
    }
}
