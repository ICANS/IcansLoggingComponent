<?php
/**
 * This file contains the MessageFactory.
 *
 * @author     Wolf Bauer
 * @copyright  2012 ICANS GmbH (http://www.icans-gmbh.com)
 */
namespace ICANS\Component\IcansLoggingComponent\Transport;

use ICANS\Component\IcansLoggingComponent\Api\V1\MessageFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Monolog\Logger;

/**
 * Definition of the MessageFactory.
 */
class MessageFactory implements MessageFactoryInterface
{
    /**
     * @var string|null
     */
    private $pulseId = null;

    /**
     * @var string
     */
    private $pulseIdPrefix;

    /**
     * @param string $pulseIdPrefix
     */
    public function __construct($pulseIdPrefix)
    {
        $this->pulseIdPrefix = $pulseIdPrefix;
    }

    /**
     * {@inheritDoc}
     */
    public function createMessage(
        $type,
        $handle,
        $version,
        array $body,
        $originType,
        $originServiceType,
        $originServiceComponent,
        $originServiceInstance,
        $logLevelValue = Logger::DEBUG,
        $logLevelName = 'DEBUG'
    )
    {
        // use the same pulseId for one request lifetime.
        if ($this->pulseId === null) {
            $this->pulseId = $this->generatePulseId();
        }

        return $this->createMessageWithPulseId(
            $type,
            $handle,
            $version,
            $body,
            $originType,
            $originServiceType,
            $originServiceComponent,
            $originServiceInstance,
            $this->pulseId,
            $logLevelValue,
            $logLevelName
        );
    }

    /**
     * {@inheritDoc}
     */
    public function createMessageWithPulseId(
        $type,
        $handle,
        $version,
        array $body,
        $originType,
        $originServiceType,
        $originServiceComponent,
        $originServiceInstance,
        $pulseId,
        $logLevelValue = Logger::DEBUG,
        $logLevelName = 'DEBUG'
    )
    {
        // in case we do not have a valid pulse id generate one
        if ($pulseId === null) {
            $pulseId = $this->generatePulseId();
        }

        $message = new Message($pulseId, $type, $handle, $version);

        $message->setCreationTimeStampInMilliseconds(new MilliSecondDateTime());
        $message->setEnvelopeVersion(self::ENVELOPE_VERSION);

        $hostname = gethostname();
        if ($hostname !== FALSE) {
            $message->setOriginHost($hostname);
        } elseif (isset($_SERVER['HTTP_HOST'])) {
            $message->setOriginHost($_SERVER['HTTP_HOST']);
        } else {
            $message->setOriginHost('hostname_not_available');
        }

        $message->setOriginType($originType);
        $message->setOriginServiceType($originServiceType);
        $message->setOriginServiceComponent($originServiceComponent);
        $message->setOriginServiceInstance($originServiceInstance);

        $message->setLogLevel($logLevelValue, $logLevelName);

        $message->setBody($body);

        return $message;
    }

    /**
     * {@inheritDoc}
     */
    public function generatePulseId()
    {
        return $this->pulseIdPrefix . uniqid();
    }
}
