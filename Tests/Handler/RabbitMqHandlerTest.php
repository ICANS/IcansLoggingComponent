<?php
/**
 * Declares the RabbitMqHandlerTest class.
 *
 * @author    Oliver Peymann
 * @copyright 2012 ICANS GmbH (http://www.icans-gmbh.com)
 */
namespace ICANS\Bundle\IcansLoggingBundle\Tests\Handler;

use ICANS\Component\IcansLoggingComponent\Handler\RabbitMqHandler;
use ICANS\Component\IcansLoggingComponent\Api\V1\AMQPMessageProducerInterface;

use Monolog\Formatter\FormatterInterface;
use Monolog\Logger;
/**
 * Test class for the rabbit mq handler
 */
class RabbitMqHandlerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $messageProducerMock;

    /**
     * @var RabbitMqHandler
     */
    private $rabbitMqHandler;

    /**
     * @var string
     */
    private $vNode = 'vNodeMock';

    /**
     * @var string
     */
    private $routingKey = 'routingKeyMock';

    /**
     * @var FormatterInterface
     */
    private $formatterMock;

    /**
     * set up method for the test
     */
    public function setUp()
    {
        $this->messageProducerMock = $this->getMockBuilder(
            'ICANS\Component\IcansLoggingComponent\Api\V1\AMQPMessageProducerInterface'
        )->getMock();

        $this->formatterMock = $this->getMockBuilder('Monolog\Formatter\FormatterInterface')
            ->getMock();

        $this->rabbitMqHandler = new RabbitMqHandler($this->routingKey);
        $this->rabbitMqHandler->setEventMessageProducer($this->messageProducerMock);
    }

    /**
     * tests the handle and write() function
     */
    public function testHandleAndWrite()
    {
        $testRecord = array(
            'level' => Logger::INFO,
            'testmessage' => 'testmessage',
            'datetime' => new \DateTime(),
            'extra' => array(),
            'formatted' => array()
        );

        $testProperties= array(
            'application_headers' => array(
                "x-riak-target-vnode" => array(
                    "S", $this->vNode
                )
            )
        );

        $this->formatterMock->expects($this->once())
            ->method('format')
            ->with($testRecord)
            ->will($this->returnValue(array()));

        $this->messageProducerMock->expects($this->once())
            ->method('publish')
            ->with(json_encode($testRecord), $this->routingKey, $testProperties);

        $this->rabbitMqHandler->addAdditionalProperties($testProperties);
        $this->rabbitMqHandler->setFormatter($this->formatterMock);
        $this->assertTrue($this->rabbitMqHandler->handle($testRecord));
    }

    /**
     * tests the handle and write() function and checks if invalid UTF-8 sequences are converted to json-fyable ones
     */
    public function testHandleAndWriteWithInvalidUTF8()
    {
        // create an array containing invalid utf-8 csequences and also create the array which would be the outcome
        // after 'fixing' these invalid utf-8 chars
        $validUtf8 = 'Paição';
        $invalidUtf8 = mb_convert_encoding($validUtf8, 'ISO-8859-1', 'UTF-8');
        $testRecord = array(
            'level' => Logger::INFO,
            'testmessage' => $invalidUtf8,
            'testDeeperArray' => array(
                $invalidUtf8 => $invalidUtf8
            ),
            'formatted' => array()
        );

        $expectedjsonfied = json_encode(array(
            'level' => Logger::INFO,
            'testmessage' => $validUtf8,
            'testDeeperArray' => array(
                $validUtf8 => $validUtf8
            ),
            'formatted' => array()
        ));

        $testProperties= array(
            'application_headers' => array(
                "x-riak-target-vnode" => array(
                    "S", $this->vNode
                )
            )
        );

        $this->formatterMock->expects($this->once())
            ->method('format')
            ->with($testRecord)
            ->will($this->returnValue(array()));

        $this->messageProducerMock->expects($this->once())
            ->method('publish')
            ->with($expectedjsonfied, $this->routingKey, $testProperties);

        $this->rabbitMqHandler->addAdditionalProperties($testProperties);
        $this->rabbitMqHandler->setFormatter($this->formatterMock);
        $this->assertTrue($this->rabbitMqHandler->handle($testRecord));
    }

    public function testHandleAndWriteWithUnavailableProducer()
    {
        $testRecord = array(
            'level' => Logger::INFO,
            'testmessage' => 'testmessage',
            'datetime' => new \DateTime(),
            'extra' => array(),
            'formatted' => array()
        );

        $testProperties= array(
            'application_headers' => array(
                "x-riak-target-vnode" => array(
                    "S", $this->vNode
                )
            )
        );

        $this->formatterMock->expects($this->once())
            ->method('format')
            ->with($testRecord)
            ->will($this->returnValue(array()));

        $this->messageProducerMock->expects($this->once())
            ->method('publish')
            ->with(json_encode($testRecord), $this->routingKey, $testProperties)
            ->will($this->throwException(new \ErrorException('AMQPConnection failed')));


        $this->rabbitMqHandler->addAdditionalProperties($testProperties);
        $this->rabbitMqHandler->setFormatter($this->formatterMock);
        $this->assertFalse($this->rabbitMqHandler->handle($testRecord));
        $this->assertTrue($this->rabbitMqHandler->getBubble());
    }
}
