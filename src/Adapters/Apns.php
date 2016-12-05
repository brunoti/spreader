<?php

namespace Indb\Spreader\Adapters;

use Indb\Spreader\Support\Adapter;
use Indb\Spreader\Events\PushWasSent;
use Indb\Spreader\Events\MessageWasSent;
use Indb\Spreader\Models\DeviceContract;
use Indb\Spreader\Models\MessageContract;
use Indb\Spreader\Exeptions\AdapterException;

use ZendService\Apple\Apns\Client\AbstractClient      as ServiceAbstractClient;
use ZendService\Apple\Apns\Client\Message             as ServiceClient;
use ZendService\Apple\Apns\Message                    as ServiceMessage;
use ZendService\Apple\Apns\Message\Alert              as ServiceAlert;
use ZendService\Apple\Apns\Response\Message           as ServiceResponse;
use ZendService\Apple\Apns\Exception\RuntimeException as ServiceRuntimeException;
use ZendService\Apple\Apns\Client\Feedback            as ServiceFeedbackClient;

class Apns extends Adapter
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
	 * @throws AdapterException
	 */
	public function __construct(array $parameters = [])
	{
		parent::__construct($parameters);

		$cert = $this->getParameter('certificate');

		if (false === file_exists($cert)) {
			throw new AdapterException(sprintf('Certificate %s does not exist', $cert));
		}
	}

	public function send($push)
	{
		$client = $this->getConnectedClient();

		foreach($push->getDevices() as $device) {
			$evelop = $this->createEnvelope($device, $push->getMessage());

			try {
				$response = $client->send($envelop);
			} catch (RuntimeException $error) {
				event(new MessageWasNotSent($error));
				continue;
			}

			event(new MessageWasSent($response));
		}
	}

	public function createEnvelope(DeviceContract $device, MessageContract $message)
	{
		$badge = ($message->hasOption('badge'))
			? (int) ($message->getOption('badge') + $device->getParameter('badge', 0))
			: false;

		$sound            = $message->getOption('sound', 'bingbong.aiff');
		$contentAvailable = $message->getOption('content-available');
		$category         = $message->getOption('category');

		$alert = new ServiceAlert(
			$message->getText(),
			$message->getOption('actionLocKey'),
			$message->getOption('locKey'),
			$message->getOption('locArgs'),
			$message->getOption('launchImage'),
			$message->getOption('title'),
			$message->getOption('titleLocKey'),
			$message->getOption('titleLocArgs')
		);

		if ($actionLocKey = $message->getOption('actionLocKey')) {
			$alert->setActionLocKey($actionLocKey);
		}
		if ($locKey = $message->getOption('locKey')) {
			$alert->setLocKey($locKey);
		}
		if ($locArgs = $message->getOption('locArgs')) {
			$alert->setLocArgs($locArgs);
		}
		if ($launchImage = $message->getOption('launchImage')) {
			$alert->setLaunchImage($launchImage);
		}
		if ($title = $message->getOption('title')) {
			$alert->setTitle($title);
		}
		if ($titleLocKey = $message->getOption('titleLocKey')) {
			$alert->setTitleLocKey($titleLocKey);
		}
		if ($titleLocArgs = $message->getOption('titleLocArgs')) {
			$alert->setTitleLocArgs($titleLocArgs);
		}

		$serviceMessage = new ServiceMessage();

		$serviceMessage->setId(sha1($device->getToken().$message->getText()));

		$serviceMessage->setAlert($alert);

		$serviceMessage->setToken($device->getToken());

		if (false !== $badge) {
			$serviceMessage->setBadge($badge);
		}

		$serviceMessage->setCustom($message->getOption('custom', []));

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
			$this->isProductionEnvironment() ? ServiceClient::PRODUCTION_URI : ServiceClient::SANDBOX_URI,
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

	public function getConnectedClient()
	{
		if (!isset($this->client)) {
			$this->client = $this->connect();
		}

		return $this->client;
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
		return ['passPhrase' => null];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRequiredParameters()
	{
		return ['certificate'];
	}
}
