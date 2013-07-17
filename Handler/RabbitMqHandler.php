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
     * @param int    $level The minimum logging level at which this handler will be triggered
     * @param bool   $bubble Whether the messages that are handled can bubble up the stack or not
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
     * @param string $propertyKey
     * @param string $property
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

    /**
     * {@inheritDoc}
     */
    protected function handleWrite(array $record)
    {
        $this->bubble = true;
        $producer = $this->getEventMessageProducer();

        if (null !== $producer) {
            try {
                // Check the given record for wrong encoding
                // see doclbock of private function recursiveFixencoding
                $encodedRecord = @json_encode($record);

                if (json_last_error() == JSON_ERROR_UTF8) {
                    $jsonProofRecord = $this->recursiveFixEncoding($record);
                    $encodedRecord = json_encode($jsonProofRecord);
                }

                $producer->publish(
                    $encodedRecord,
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
    protected function checkIsHandling(array $record)
    {
        return null !== $this->getEventMessageProducer();
    }

    /**
     * recursive function which checks the keys and values of an array for invalid utf-8 sequences as they may
     * produce errors when doing json_encode on them
     * @todo this should be done somewhere else - the best way would be to inject a smart serializer here
     *
     * @param array $array
     * @return array
     */
    private function recursiveFixEncoding(array $array) {
        if (!is_array($array)) {
            return $array;
        }
        $result = array();
        foreach ($array as $key => $value) {

            $validUtf = mb_check_encoding($key, 'UTF-8');
            if (!$validUtf) {
                $key = mb_convert_encoding($key, 'UTF-8');
            }

            if (is_array($value)) {
                $result[$key] = $this->recursiveFixEncoding($value);
            } else {
                $validUtf = mb_check_encoding($value, 'UTF-8');
                if (!$validUtf) {
                    $value = mb_convert_encoding($value, 'UTF-8');
                }
                $result[$key] = $value;
            }
        }
        return $result;
    }
}
