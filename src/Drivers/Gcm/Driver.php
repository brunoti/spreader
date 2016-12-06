<?php
namespace Indb\Spreader\Drivers\Gcm;

use Indb\Spreader\Models\PushContract;
use Indb\Spreader\Exceptions\PushException;
use Indb\Spreader\Drivers\Gcm\Jobs\QueuePush;
use Indb\Spreader\Collections\DeviceCollection;
use Indb\Spreader\Support\Driver as BaseDriver;

use Zend\Http\Client as HttpClient;
use Zend\Http\Client\Driver\Socket as HttpSocketDriver;

use ZendService\Google\Gcm\Client as ServiceClient;
use ZendService\Google\Gcm\Message as ServiceMessage;
use ZendService\Google\Exception\RuntimeException as ServiceRuntimeException;

use Indb\Spreader\Drivers\Gcm\Events\MessageWasSent;
use Indb\Spreader\Drivers\Gcm\Events\MessageWasNotSent;

use InvalidArgumentException;

class Driver extends BaseDriver
{
    /**
     * @var \Zend\Http\Client
     */
    private $httpClient;

    /**
     * @var ServiceClient
     */
    private $openedClient;

    /**
     * {@inheritdoc}
     */
    public function send(PushContract $push)
    {
        $tokens = $push->getDevices()->getTokens()->chunk(100)->toArray();

        if($this->getParameter('useQueue')) {

            foreach ($tokens as $tokensRange) {
                $message = $this->getServiceMessageFromOrigin($tokensRange, $push->getMessage());
                $job = (new QueuePush($this, $message))->onQueue($this->getDriverName());
                $this->dispatch($job);
            }

            return null;
        }

        $pushedDevices = [];
        $client = $this->getOpenedClient();

        foreach ($tokens as $tokensRange) {
            $message = $this->getServiceMessageFromOrigin($tokensRange, $push->getMessage());


            try {
                $response = $client->send($message);
            } catch (ServiceRuntimeException $error) {
                event(new MessageWasNotSent($message, $error));
            }

            event(new MessageWasSent($response, $message));

            if ((bool) $response->getSuccessCount()) {
                foreach ($tokensRange as $token) {
                    $pushedDevices[] = $token;
                }
            }
        }

        return $pushedDevices;
    }

    /**
     * Get opened client
     *
     * @return \ZendService\Google\Gcm\Client
     */
    public function getOpenedClient()
    {
        if (!isset($this->openedClient)) {
            $this->openedClient = new ServiceClient();
            $this->openedClient->setApiKey($this->getParameter('apiKey'));

            $newClient = new \Zend\Http\Client(
                null,
                [
                    'driver' => 'Zend\Http\Client\Driver\Socket',
                    'sslverifypeer' => false
                ]
            );

            $this->openedClient->setHttpClient($newClient);
        }

        return $this->openedClient;
    }

    /**
     * Get service message from origin
     *
     * @param array $tokens Tokens
     * @param BaseOptionedModel|\Indb\Spreader\Model\MessageInterface $message Message
     *
     * @return \ZendService\Google\Gcm\Message
     */
    public function getServiceMessageFromOrigin(array $tokens, $message)
    {
        $data            = $message->getParameters();
        $data['message'] = $message->getText();

        $serviceMessage = new ServiceMessage();
        $serviceMessage->setRegistrationIds($tokens);
        $serviceMessage->setData($data);
        $serviceMessage->setCollapseKey($this->getParameter('collapseKey'));
        $serviceMessage->setRestrictedPackageName($this->getParameter('restrictedPackageName'));
        $serviceMessage->setDelayWhileIdle($this->getParameter('delayWhileIdle', false));
        $serviceMessage->setTimeToLive($this->getParameter('ttl', 600));
        $serviceMessage->setDryRun($this->getParameter('dryRun', false));

        return $serviceMessage;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefinedParameters()
    {
        return [
            'collapse_key',
            'delay_while_idle',
            'time_to_live',
            'restricted_package_name',
            'dry_run'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultParameters()
    {
        return [
            'useQueue' => false
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getRequiredParameters()
    {
        return ['apiKey'];
    }

    /**
     * Get the current Zend Http Client instance
     *
     * @return HttpClient
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * Overrides the default Http Client
     *
     * @param HttpClient $client
     */
    public function setHttpClient(HttpClient $client)
    {
        $this->httpClient = $client;
    }

    /**
     * Send custom parameters to the Http Driver without overriding the Http Client
     *
     * @param array $config
     *
     * @throws \InvalidArgumentException
     */
    public function setDriverParameters(array $config = [])
    {
        if (!is_array($config) || empty($config)) {
            throw new InvalidArgumentException(
                '$config must be an associative array with at least 1 item'
            );
        }

        if ($this->httpClient === null) {
            $this->httpClient = new HttpClient();
            $this->httpClient->setAdapter(new HttpSocketDriver());
        }

        $this->httpClient->getAdapter()->setOptions($config);
    }
}
