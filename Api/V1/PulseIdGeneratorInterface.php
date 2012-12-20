<?php
/**
 * This file contains the PulseIdGeneratorInterface.
 *
 * @author     Malte Stenzel
 * @copyright  2012 ICANS GmbH (http://www.icans-gmbh.com)
 */
namespace ICANS\Component\IcansLoggingComponent\Api\V1;

/**
 * Definition of the MessageFactoryInterface.
 */
interface PulseIdGeneratorInterface
{
    /**
     * Generate a new pulse id.
     *
     * @see http://devblog.icans-gmbh.com/passion-the-request-pulse/
     *
     * @return string
     */
    public function generatePulseId();
}
