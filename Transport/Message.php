<?php
/**
 * This file contains the Message.
 *
 * @author     Wolf Bauer
 * @copyright  2012 ICANS GmbH (http://www.icans-gmbh.com)
 */
namespace ICANS\Component\IcansLoggingComponent\Transport;

use ICANS\Component\IcansLoggingComponent\Api\V1\MessageInterface;

/**
 * Definition of the Message.
 */
class Message extends AbstractTransportObject implements MessageInterface
{
    /**
     * Create a new instance.
     *
     * @param string $pulseId
     * @param string $type
     * @param string $handle
     * @param int    $version
     */
    public function __construct($pulseId, $type, $handle, $version)
    {
        $this->setStringValue(self::PULSE_ID, $pulseId);
        $this->setStringValue(self::TYPE, $type);
        $this->setStringValue(self::HANDLE, $handle);
        $this->setIntegerValue(self::VERSION, $version);
    }

    /**
     * @param string $originType
     */
    public function setOriginType($originType)
    {
        $this->setStringValue(self::ORIGIN_TYPE, $originType);
    }

    /**
     * @param MilliSecondDateTime $creationDateTime
     */
    public function setCreationTimeStampInMilliseconds(MilliSecondDateTime $creationDateTime)
    {
        $this->setDoubleValue(self::CREATED_TIMESTAMP, $creationDateTime->getTimestampInMilliseconds());
        $this->setStringValue(self::CREATED_DATE, $creationDateTime->getDateStringWithMilliseconds());
    }

    /**
     * @param int $version
     */
    public function setEnvelopeVersion($version)
    {
        $this->setIntegerValue(self::ENVELOPE_VERSION, $version);
    }

    /**
     * @param string $originServiceType
     */
    public function setOriginServiceType($originServiceType)
    {
        $this->setStringValue(self::ORIGIN_SERVICE_TYPE, $originServiceType);
    }

    /**
     * @param string $originServiceComponent
     */
    public function setOriginServiceComponent($originServiceComponent)
    {
        $this->setStringValue(self::ORIGIN_SERVICE_COMPONENT, $originServiceComponent);
    }

    /**
     * @param string $originServiceInstance
     */
    public function setOriginServiceInstance($originServiceInstance)
    {
        $this->setStringValue(self::ORIGIN_SERVICE_INSTANCE, $originServiceInstance);
    }

    /**
     * @param string $logLevelValue
     * @param string $logLevelName
     */
    public function setLogLevel($logLevelValue, $logLevelName)
    {
        $this->setIntegerValue(self::LOGLEVEL_VALUE, $logLevelValue);
        $this->setStringValue(self::LOGLEVEL, $logLevelName);
        $this->setIntegerValue(self::PROPRIATE_LOGLEVEL_VALUE, $logLevelValue);
    }

    /**
     * @param string $originHost
     */
    public function setOriginHost($originHost)
    {
        $this->setStringValue(self::ORIGIN_HOST, $originHost);
    }

    /**
     * @param array $body
     */
    public function setBody(array $body)
    {
        $this->setArrayValue(self::BODY, $body);
    }

    /**
     * {@inheritDoc}
     */
    public function getRawData()
    {
        return $this->rawData;
    }

}
