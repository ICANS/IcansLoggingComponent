<?php
/**
 * Declares the PulseIdProviderInterface class.
 *
 * @author    Nikolaus Schlemm <nikolaus.schlemm@icans-gmbh.com>
 * @copyright 2013 ICANS GmbH (http://www.icans-gmbh.com)
 */
namespace ICANS\Component\IcansLoggingComponent\Api\V1;

/**
 * Describes a pulse id provider - useful for encapsulating different implementations for retrieving it.
 *
 * @package Icans\Ecf\Bundle\LoggingBundle\Api\V1
 */
interface PulseIdProviderInterface
{
    /**
     * Get the pulseId to be used for logging.
     *
     * @return string
     */
    public function getPulseId();
}