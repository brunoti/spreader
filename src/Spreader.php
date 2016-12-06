<?php

namespace Indb\Spreader;

use Indb\Spreader\Models\PushContract;
use Indb\Spreader\Support\Traits\Parameters;
use Indb\Spreader\Exceptions\DriverException;
use Indb\Spreader\Support\Contracts\DriverContract;
use Indb\Spreader\Support\Contracts\ParameterContract;

class Spreader implements ParameterContract
{
    use Parameters;

    const ENVIRONMENT_DEV  = 'dev';
    const ENVIRONMENT_PROD = 'prod';

    /**
     * @var array<Push>
     */
    private $pushes = [];

    /**
     * Constructor
     *
     * @param array $parameters - Parameters of the spreader
     */
    public function __construct(array $parameters = [])
    {
        $this->start($parameters);
    }

    public function add(PushContract $push)
    {
        array_push($this->pushes, $push);

        return $this;
    }

    public function send()
    {
        foreach($this->pushes as $push) {
            $push
                ->getDriver()
                ->setEnvironment($this->getParameter('env'))
                ->send($push);
        }
    }

    /**
     * Get the driver feedback if is possible
     *
     * @param DriverContract $driver
     *
     * @return mixed
     *
     * @throws DriverException - When the driver has no dedicated `getFeedback` method
     */
    public function getFeedback(DriverContract $driver)
    {
        if (false === method_exists($driver, 'getFeedback')) {
            throw new DriverException(
                sprintf(
                    '%s driver has no dedicated "getFeedback" method',
                    (string) $driver
                )
            );
        }

       return $driver->setEnvironment($this->getParameter('env'))->getFeedback();
    }

    /**
     * {@inheritdoc}
     */
    public function getDefinedParameters()
    {
        return [ 'env', 'useQueue' ];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultParameters()
    {
        return [
            'env' => self::ENVIRONMENT_PROD,
            'useQueue' => false,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getRequiredParameters()
    {
        return [];
    }
}
