<?php
/**
 * This file contains the interface needed to be implemented for a AMQPProducer.
 *
 * PHP Version 5.3
 *
 * @author    Mike Lohmann <mike.lohmann@icans-gmbh.com>
 * @copyright 2012 ICANS GmbH
 */
namespace ICANS\Component\IcansLoggingComponent\Api\V1;

/**
 * This class defines the interface needed for AMQPMessageProducers
 *
 * @author    Mike Lohmann <mike.lohmann@icans-gmbh.com>
 * @copyright 2011 ICANS GmbH
 */
interface AMQPMessageProducerInterface
{
    /**
     * This is copied from the OldSoundBundle\RabbitMq\Producer::publish definition.
     * We put this in our own interface to be independent from the Bundle in our Component.
     *
     * Perhaps there is a way to extract some of the implementation in the OldSoundRabbitMqBundle
     * to a Component.
     *
     * Publishes the message and merges additional properties with basic properties
     *
     * @param string $msgBody
     * @param string $routingKey
     * @param array $additionalProperties
     */
    public function publish($msgBody, $routingKey = '', $additionalProperties = array());

}