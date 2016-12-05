<?php

namespace Indb\Spreader;

use Indb\Spreader\Models\PushContract;

class Spreader
{
    const ENVIRONMENT_DEV  = 'dev';
    const ENVIRONMENT_PROD = 'prod';

    /**
     * @var string
     */
    private $environment;

    /**
     * @var array<Push>
     */
    private $pushes;

    /**
     * Constructor
     *
     * @param string $environment Environment
     */
    public function __construct($environment = self::ENVIRONMENT_DEV)
    {
        $this->environment = $environment;
    }

    public function add(PushContract $push)
    {
        array_push($this->pushes, $push);

        return $this;
    }

    public function send()
    {
        foreach($pushes as $push) {
            $adapter = $push->getAdapter()->setEnvironment($this->environment);
            $adapter->send($push);
            event(new PushWasSent($adapter));
        }
    }
}
