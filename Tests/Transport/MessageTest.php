<?php
/**
 * Unit tests for ICANS\Component\IcansLoggingComponent\Transport\Message.
 *
 * @author    Carsten Bluem <carsten.bluem@icans-gmbh.com>
 * @copyright 2012 ICANS GmbH (http://www.icans-gmbh.com)
 */
namespace ICANS\Component\IcansLoggingComponent\Transport;

/**
 * Unit tests for ICANS\Component\IcansLoggingComponent\Transport\Message.
 */
class MessageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Message
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Message(
            'mypulseid',
            'test-type',
            'test-handle',
            '1'
        );
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->object = null;
    }

    /**
     * @covers ICANS\Component\IcansLoggingComponent\Transport\Message::__construct
     */
    public function testTheConstructor()
    {
        $this->assertInstanceOf('ICANS\Component\IcansLoggingComponent\Transport\Message', $this->object);
    }

    /**
     * @covers ICANS\Component\IcansLoggingComponent\Transport\Message::getRawData
     */
    public function testGetRawData()
    {
        $actual = $this->object->getRawData();
        $expected = array(
            'pulse'=>'mypulseid',
            'event_type'=>'test-type',
            'event_handle'=>'test-handle',
            'event_version'=>1,
        );

        $this->assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers ICANS\Component\IcansLoggingComponent\Transport\Message::setOriginType
     * @depends testGetRawData
     */
    public function testSetTheOriginType()
    {
        $originType = 'test-origin-type';
        $this->object->setOriginType($originType);
        $actual = $this->object->getRawData();
        $this->assertArrayHasKey('origin_type', $actual);
        $this->assertSame($actual['origin_type'], $originType);
    }

    /**
     * @covers ICANS\Component\IcansLoggingComponent\Transport\Message::setCreationTimeStampInMilliseconds
     * @depends testGetRawData
     */
    public function testSetCreationTimeStampInMilliseconds()
    {
        $milliseconds = 1351072471234;
        $this->object->setCreationTimeStampInMilliseconds($milliseconds);
        $actual = $this->object->getRawData();
        $this->assertArrayHasKey('created_timestamp', $actual);
        $this->assertEquals($milliseconds, $actual['created_timestamp']);
    }

    /**
     * @covers ICANS\Component\IcansLoggingComponent\Transport\Message::setEnvelopeVersion
     * @depends testGetRawData
     */
    public function testSetEnvelopeVersion()
    {
        $envelopeVersion = 12345;
        $this->object->setEnvelopeVersion($envelopeVersion);
        $actual = $this->object->getRawData();
        $this->assertArrayHasKey('envelope_version', $actual);
        $this->assertSame($actual['envelope_version'], $envelopeVersion);
    }

    /**
     * @covers ICANS\Component\IcansLoggingComponent\Transport\Message::setOriginServiceType
     * @depends testGetRawData
     */
    public function testSetOriginServiceType()
    {
        $originServiceType = 'test-origin-service-type';
        $this->object->setOriginServiceType($originServiceType);
        $actual = $this->object->getRawData();
        $this->assertArrayHasKey('origin_service_type', $actual);
        $this->assertSame($actual['origin_service_type'], $originServiceType);
    }

    /**
     * @covers ICANS\Component\IcansLoggingComponent\Transport\Message::setOriginServiceComponent
     * @depends testGetRawData
     */
    public function testSetOriginServiceComponent()
    {
        $originServiceComponent = 'test-origin-service-component';
        $this->object->setOriginServiceComponent($originServiceComponent);
        $actual = $this->object->getRawData();
        $this->assertArrayHasKey('origin_service_component', $actual);
        $this->assertSame($actual['origin_service_component'], $originServiceComponent);
    }

    /**
     * @covers ICANS\Component\IcansLoggingComponent\Transport\Message::setOriginServiceInstance
     * @depends testGetRawData
     */
    public function testSetOriginServiceInstance()
    {
        $originServiceInstance = 'test-origin-service-instance';
        $this->object->setOriginServiceInstance($originServiceInstance);
        $actual = $this->object->getRawData();
        $this->assertArrayHasKey('origin_service_instance', $actual);
        $this->assertSame($actual['origin_service_instance'], $originServiceInstance);
    }

    /**
     * @covers ICANS\Component\IcansLoggingComponent\Transport\Message::setLogLevel
     * @depends testGetRawData
     */
    public function testSetLogLevel()
    {
        $logLevelValue = 300;
        $logLevelName = 'log-level-testing';
        $this->object->setLogLevel($logLevelValue, $logLevelName);
        $actual = $this->object->getRawData();
        $this->assertArrayHasKey('message_loglevel_value', $actual);
        $this->assertArrayHasKey('message_loglevel', $actual);
        $this->assertSame($actual['message_loglevel_value'], $logLevelValue);
        $this->assertSame($actual['message_loglevel'], $logLevelName);
    }

    /**
     * @covers ICANS\Component\IcansLoggingComponent\Transport\Message::setBody
     * @depends testGetRawData
     */
    public function testSetBody()
    {
        $body = array(
            'Body Item 1',
            'Body Item 2',
        );
        $this->object->setBody($body);
        $actual = $this->object->getRawData();
        $this->assertArrayHasKey('event_body', $actual);
        $this->assertSame($actual['event_body'], $body);
    }

}
