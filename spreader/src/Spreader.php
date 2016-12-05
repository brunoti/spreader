<?php

namespace Indb\Spreader;

use Indb\Spreader\Models\PushContract;
use Indb\Spreader\Support\Traits\Parameters;
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
            $driver = $push->getDriver()->setEnvironment($this->getParameter('env'));
            $driver->send($push);
        }
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
