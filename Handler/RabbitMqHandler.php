<?php
/**
 * Declares the RabbitMqHandler class.
 *
 * @author    Oliver Peymann
 * @author    Mike Lohmann
 * @copyright 2012 ICANS GmbH (http://www.icans-gmbh.com)
 */
namespace ICANS\Component\IcansLoggingComponent\Handler;

use ICANS\Component\IcansLoggingComponent\Handler\AbstractHandler;
use ICANS\Component\IcansLoggingComponent\Api\V1\AMQPMessageProducerInterface;

use Monolog\Logger;

/**
 * RabbitMqHandler class for sending event message to a rabbit mq instance
 */
class RabbitMqHandler extends AbstractHandler
{
    /**
     * @var AMQPMessageProducerInterface
     */
    private $eventMessageProducer = null;

    /**
     * @var string
     */
    private $routingKey;

    /**
     * @var array
     */
    private $additionalProperties;

    /**
     * Default constructor
     *
     * @param string $routingKey
     * @param int $level The minimum logging level at which this handler will be triggered
     * @param bool $bubble Whether the messages that are handled can bubble up the stack or not
     */
    public function __construct(
        $routingKey,
        $level = Logger::DEBUG,
        $bubble = true // => has to be set to "false" after successfull message handling
    )
    {
        $this->routingKey = $routingKey;
        parent::__construct($level, $bubble);
    }

    /**
     * Add additional properties for the publish action.
     *
     * @param $propertyKey
     * @param $property
     */
    public function addAdditionalProperty($propertyKey, $property)
    {
        $this->additionalProperties[$propertyKey] = $property;
    }

    /**
     * Adds a bunsh of properties to be used in the publish action
     *
     * @param array $properties
     */
    public function addAdditionalProperties(array $properties)
    {
        $this->additionalProperties = $properties;
    }

    /**
     * {@inheritDoc}
     */
    protected function write(array $record)
    {
        if (!empty($this->writeFilters)) {
            foreach ($this->writeFilters as $filter) {
                if (true === $filter->isRecordToBeFiltered($record)) {
                    $record = $filter->filterRecord($record);
                }
            }
        }

        $this->bubble = true;
        $producer = $this->getEventMessageProducer();

        if (null !== $producer) {
            try {
                $producer->publish(
                    json_encode($record),
                    $this->routingKey,
                    $this->additionalProperties
                );
                $this->bubble = false; // = the record was successfully consumed
            } catch (\Exception $e) {
                $this->bubble = true; // = the record was NOT successfully consumed
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function isHandling(array $record)
    {
        if (true === $this->isHandlingStopped()) {
            return false;
        }

        if (null === $this->getEventMessageProducer()) {
            return false;
        }

        if (!empty($this->handlingFilters)) {
            foreach ($this->handlingFilters as $filter) {
                if (true === $filter->isRecordToBeHandled($record)) {
                    return false;
                }
            }
        }

        return parent::isHandling($record);
    }

    /**
     * {@inheritDoc}
     */
    public function close()
    {
        $this->handlingStopped = true;
    }

    /**
     * Adds a messageProducer which knows AMQP
     *
     * @param AMQPMessageProducerInterface $messageProducer
     */
    public function setEventMessageProducer(AMQPMessageProducerInterface $messageProducer)
    {
        $this->eventMessageProducer = $messageProducer;
    }

    /**
     * Helper function wrapping the EventMessageProducer to cater for graceful handling of AMQP failures.
     *
     * @return AMQPMessageProducerInterface|null
     */
    public function getEventMessageProducer()
    {
        return $this->eventMessageProducer;
    }
}
