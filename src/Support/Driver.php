<?php
namespace Indb\Spreader\Support;

use ReflectionClass;
use Indb\Spreader\Spreader;
use Indb\Spreader\Support\Traits\Parameters;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Indb\Spreader\Support\Contracts\DriverContract;


abstract class Driver implements DriverContract
{
    use Parameters, DispatchesJobs;

    /**
     * @var string
     */
    protected $environment;

    /**
     * @var string
     */
    protected $driverName;

    /**
     * Constructor
     *
     * @param array $parameters Driver specific parameters
     */
    public function __construct(array $parameters = [])
    {
        $this->start($parameters);
    }


    /**
     * Setter for the driver name
     *
     * @param string $driverName
     *
     * @return self
     */
    public function setDriverName($driverName)
    {
        $this->driverName = $driverName;
        return $this;
    }


    /**
     * Getter for the driverName
     *
     * @return string
     */
    public function getDriverName()
    {
        return $this->driverName;
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
