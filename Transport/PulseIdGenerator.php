<?php
/**
 * This file contains the Pulse id generator.
 *
 * @author     Malte Stenzel
 * @copyright  2012 ICANS GmbH (http://www.icans-gmbh.com)
 */
namespace ICANS\Component\IcansLoggingComponent\Transport;

use ICANS\Component\IcansLoggingComponent\Api\V1\PulseIdGeneratorInterface;

/**
 * Definition of the MessageFactory.
 */
class PulseIdGenerator implements PulseIdGeneratorInterface
{
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
    public function generatePulseId()
    {
        return $this->pulseIdPrefix . uniqid();
    }
}
