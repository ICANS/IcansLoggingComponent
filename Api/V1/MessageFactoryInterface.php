<?php
/**
 * This file contains the MessageFactoryInterface.
 *
 * @author     Wolf Bauer
 * @copyright  2012 ICANS GmbH (http://www.icans-gmbh.com)
 */
namespace ICANS\Component\IcansLoggingComponent\Api\V1;

use ICANS\Component\IcansLoggingComponent\Api\V1\MessageInterface;

use Monolog\Logger;

/**
 * Definition of the MessageFactoryInterface.
 */
interface MessageFactoryInterface
{
    /**
     * @var string
     */
    const ENVELOPE_VERSION = 2;

    /**
     * @todo Wolf Bauer, 20121023, can we reduce the loglevel information? We do not need the log level in RPS or
     * Video service...
     *
     * @param string $type
     * @param string $handle
     * @param int    $version
     * @param array  $body
     * @param string $originType
     * @param string $originServiceType
     * @param string $originServiceComponent
     * @param string $originServiceInstance
     * @param int    $logLevelValue
     * @param string $logLevelName
     *
     * @return MessageInterface
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
    );

    /**
     * @todo Wolf Bauer, 20121023, can we reduce the loglevel information? We do not need the log level in RPS or
     * Video service...
     *
     * @param string $type
     * @param string $handle
     * @param int    $version
     * @param array  $body
     * @param string $originType
     * @param string $originServiceType
     * @param string $originServiceComponent
     * @param string $originServiceInstance
     * @param int    $logLevelValue
     * @param string $logLevelName
     * @param string $pulseId
     *
     * @return MessageInterface
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
    );

    /**
     * Generate a new pulse id.
     *
     * @see http://devblog.icans-gmbh.com/passion-the-request-pulse/
     *
     * @return string
     */
    public function generatePulseId();
}
