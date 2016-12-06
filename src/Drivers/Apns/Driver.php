<?php

namespace Indb\Spreader\Drivers\Apns;

use Indb\Spreader\Models\PushContract;
use Indb\Spreader\Models\DeviceContract;
use Indb\Spreader\Models\MessageContract;
use Indb\Spreader\Exeptions\DriverException;
use Indb\Spreader\Drivers\Apns\Jobs\QueuePush;
use Indb\Spreader\Support\Driver as BaseDriver;

use Indb\Spreader\Drivers\Apns\Events\MessageWasSent;
use Indb\Spreader\Drivers\Apns\Events\MessageWasNotSent;

use ZendService\Apple\Apns\Client\AbstractClient as ServiceAbstractClient;
use ZendService\Apple\Apns\Client\Message as ServiceClient;
use ZendService\Apple\Apns\Message as ServiceMessage;
use ZendService\Apple\Apns\Message\Alert as ServiceAlert;
use ZendService\Apple\Apns\Response\Message as ServiceResponse;
use ZendService\Apple\Apns\Exception\RuntimeException as ServiceRuntimeException;
use ZendService\Apple\Apns\Client\Feedback as ServiceFeedbackClient;

class Driver extends BaseDriver
{
    /**
     * @var ServiceClient
     */
    private $client;

    /**
     * @var ServiceFeedbackClient
     */
    private $feedbackClient;

    /**
     * {@inheritdoc}
     *
     * @throws DriverException
     */
    public function __construct(array $parameters = [])
    {
        parent::__construct($parameters);

        $cert = $this->getParameter('certificate');

        if (false === file_exists($cert)) {
            throw new DriverException(sprintf('Certificate %s does not exist', $cert));
        }
    }

    public function send(PushContract $push)
    {
        if($this->getParameter('useQueue')) {

            foreach($push->getDevices()->chunk(10) as $device_chunk) {
                $envelopes = [];

                foreach($device_chunk as $device) {
                    $envelopes[] = $this->createEnvelope($device, $push->getMessage());
                }

                $job = (new QueuePush($this, $envelopes))->onQueue($this->getDriverName());
                $this->dispatch($job);
            }


            return null;
        }

        $responses = [];
        $client = $this->getConnectedPushClient();

        foreach($push->getDevices()->toArray() as $device) {
            $envelope = $this->createEnvelope($device, $push->getMessage());

            try {
                $response = $client->send($envelope);
            } catch (ServiceRuntimeException $error) {
                event(new MessageWasNotSent($envelope, $error, $this));
                continue;
            }

            $responses[] = [
                $device->getToken() => $response
            ];

            event(new MessageWasSent($response, $envelope, $this));
        }

        return $response;
    }


    public function createEnvelope(DeviceContract $device, MessageContract $message)
    {
        $badge = ($message->hasParameter('badge'))
            ? (int) ($message->getParameter('badge') + $device->getParameter('badge', 0))
            : 1;

        $sound            = $message->getParameter('sound', 'bingbong.aiff');
        $contentAvailable = $message->getParameter('content-available');
        $category         = $message->getParameter('category');

        $alert = new ServiceAlert(
            $message->getText(),
            $message->getParameter('actionLocKey'),
            $message->getParameter('locKey'),
            $message->getParameter('locArgs'),
            $message->getParameter('launchImage'),
            $message->getParameter('title'),
            $message->getParameter('titleLocKey'),
            $message->getParameter('titleLocArgs')
        );

        if ($actionLocKey = $message->getParameter('actionLocKey')) {
            $alert->setActionLocKey($actionLocKey);
        }
        if ($locKey = $message->getParameter('locKey')) {
            $alert->setLocKey($locKey);
        }
        if ($locArgs = $message->getParameter('locArgs')) {
            $alert->setLocArgs($locArgs);
        }
        if ($launchImage = $message->getParameter('launchImage')) {
            $alert->setLaunchImage($launchImage);
        }
        if ($title = $message->getParameter('title')) {
            $alert->setTitle($title);
        }
        if ($titleLocKey = $message->getParameter('titleLocKey')) {
            $alert->setTitleLocKey($titleLocKey);
        }
        if ($titleLocArgs = $message->getParameter('titleLocArgs')) {
            $alert->setTitleLocArgs($titleLocArgs);
        }

        $serviceMessage = new ServiceMessage();

        $serviceMessage->setId(sha1($device->getToken().$message->getText()));

        $serviceMessage->setAlert($alert);

        $serviceMessage->setToken($device->getToken());

        $serviceMessage->setBadge($badge);

        $serviceMessage->setCustom($message->getParameter('custom', []));

        if (null !== $sound) {
            $serviceMessage->setSound($sound);
        }

        if (null !== $contentAvailable) {
            $serviceMessage->setContentAvailable($contentAvailable);
        }

        if (null !== $category) {
            $serviceMessage->setCategory($category);
        }

        return $serviceMessage;
    }

    private function connect(ServiceAbstractClient $client)
    {
        $client->open(
            $this->isProductionEnvironment()
            ? ServiceClient::PRODUCTION_URI
            : ServiceClient::SANDBOX_URI,
            $this->getParameter('certificate'),
            $this->getParameter('passPhrase')
        );

        return $client;
    }

    private function disconnect()
    {
        $this->client->close();

        $this->client = null;

        return $this;
    }

    public function getConnectedPushClient()
    {
        if (!isset($this->client)) {
            $this->client = $this->connect(new ServiceClient());
        }

        return $this->client;
    }

    /**
     * Get opened ServiceFeedbackClient
     *
     * @return ServiceAbstractClient
     */
    private function getConnectedFeedbackClient()
    {
        if (!isset($this->feedbackClient)) {
            $this->feedbackClient = $this->connect(new ServiceFeedbackClient());
        }

        return $this->feedbackClient;
    }

    /**
     * Get the Apns feedback
     *
     * @return array
     */
    public function getFeedback()
    {
        $client           = $this->getConnectedPushClient();
        $responses        = [];
        $serviceResponses = $client->feedback();

        return $serviceResponses;
    }


    /**
     * {@inheritdoc}
     */
    public function getDefinedParameters()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultParameters()
    {
        return [
            'passPhrase' => null,
            'useQueue'   => false
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getRequiredParameters()
    {
        return ['certificate'];
    }
}
