<?php
/**
 * Unit tests for ICANS\Component\IcansLoggingComponent\Transport\MessageFactory.
 *
 * @author    Carsten Bluem <carsten.bluem@icans-gmbh.com>
 * @copyright 2012 ICANS GmbH (http://www.icans-gmbh.com)
 */
namespace ICANS\Component\IcansLoggingComponent\Transport;

/**
 * Unit tests for ICANS\Component\IcansLoggingComponent\Transport\MessageFactory.
 */
class MessageFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MessageFactory
     */
    private $object;

    /**
     * @var string
     */
    private $pulsePrefix = 'prefix';

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new MessageFactory($this->pulsePrefix);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers ICANS\Component\IcansLoggingComponent\Transport\MessageFactory::createMessage
     * @todo   Implement testCreateMessage().
     */
    public function testCreateMessage()
    {
        $actual = $this->object->createMessage(
            'message type',
            'message handle',
            'message version',
            array(
                 'Body item 1',
                 'Body item 2',
            ),
            'app',
            'origin service type',
            'origin service component',
            'origin service instance',
            '1234',
            'log level name'
        );

        // Note: $actual->getRawData() will include two keys ("created_timestamp"
        // and ("created_date") which are based on the current timestamp in
        // microseconds (i.e. basically microtime()) which cannot be mocked.
        // Therefore we exclude them from the assertSame() below and test whether
        // the *approximately* match our expectations.
        $rawData = $actual->getRawData();

        $milliseconds = round(microtime(true) * 1000);
        $this->assertGreaterThan($milliseconds - 100, $rawData['created_timestamp']);
        $this->assertLessThan($milliseconds + 100, $rawData['created_timestamp']);
        unset($rawData['created_timestamp']);

        $this->assertSame(0, strncmp($rawData['created_date'], date('Y-m-d\TH:i:s.'), 20));
        unset($rawData['created_date']);

        // Additionally, when createMessage() is called, a random pulse ID is
        // created. As we cannot predict it and neither can perform any test
        // without relying on internals, we simply verify it's a non-empty
        // string and do nothing more.
        $this->assertInternalType('string', $rawData['pulse']);
        $this->assertTrue(strlen($rawData['pulse']) > 3);
        unset($rawData['pulse']);

        $expected = array(
            'event_type'               => 'message type',
            'event_handle'             => 'message handle',
            'event_version'            => 'message version',
            'origin_type'              => 'app',
            'envelope_version'         => '2',
            'origin_host' => gethostname(),
            'origin_service_type'      => 'origin service type',
            'origin_service_component' => 'origin service component',
            'origin_service_instance'  => 'origin service instance',
            'message_loglevel_value'   => '1234',
            'message_loglevel'         => 'log level name',
            'event_body'               => array(
                'Body item 1',
                'Body item 2',
            )
        );

        $this->assertInstanceOf('ICANS\Component\IcansLoggingComponent\Transport\Message', $actual);
        $this->assertEquals($expected, $rawData);
    }

    /**
     * @covers ICANS\Component\IcansLoggingComponent\Transport\MessageFactory::createMessageWithPulseId
     */
    public function testCreateMessageWithPulseId()
    {

        $actual = $this->object->createMessageWithPulseId(
            'message type',
            'message handle',
            'message version',
            array(
                 'Body item 1',
                 'Body item 2',
            ),
            'app',
            'origin service type',
            'origin service component',
            'origin service instance',
            'pulse id',
            1234,
            'log level name'
        );

        // Note: $actual->getRawData() will include two keys ("created_timestamp"
        // and ("created_date") which are based on the current timestamp in
        // microseconds (i.e. basically microtime()) which cannot be mocked.
        // Therefore we exclude them from the assertSame() below and test whether
        // the *approximately* match our expectations.
        $rawData = $actual->getRawData();

        $milliseconds = round(microtime(true) * 1000);
        $this->assertGreaterThan($milliseconds - 100, $rawData['created_timestamp']);
        $this->assertLessThan($milliseconds + 100, $rawData['created_timestamp']);
        unset($rawData['created_timestamp']);

        $this->assertSame(0, strncmp($rawData['created_date'], date('Y-m-d\TH:i:s.'), 20));
        unset($rawData['created_date']);

        $expected = array(
            'pulse'                   =>'pulse id',
            'event_type'              =>'message type',
            'event_handle'            =>'message handle',
            'event_version'           =>'message version',
            'origin_type'             =>'app',
            'envelope_version'        =>'2',
            'origin_host'             =>gethostname(),
            'origin_service_type'     =>'origin service type',
            'origin_service_component'=>'origin service component',
            'origin_service_instance' =>'origin service instance',
            'message_loglevel_value'  =>1234,
            'message_loglevel'        =>'log level name',
            'event_body'              =>array(
                'Body item 1',
                'Body item 2',
            )
        );

        $this->assertInstanceOf('ICANS\Component\IcansLoggingComponent\Transport\Message', $actual);
        $this->assertEquals($expected, $rawData);

    }

    /**
     * Test for ICANS\Component\IcansLoggingComponent\Transport\MessageFactory::createMessageWithPulseId
     */
    public function testCreateMessageWithPulseIdWithPassingNullAsPulseId()
    {

        $actual = $this->object->createMessageWithPulseId(
            'message type',
            'message handle',
            'message version',
            array(
                 'Body item 1',
                 'Body item 2',
            ),
            'app',
            'origin service type',
            'origin service component',
            'origin service instance',
            null,
            1234,
            'log level name'
        );

        // Additionally, when createMessage() is called, a random pulse ID is
        // created. As we cannot predict it and neither can perform any test
        // without relying on internals, we simply verify it's a non-empty
        // string and do nothing more.
        $rawData = $actual->getRawData();

        $this->assertInternalType('string', $rawData['pulse']);
        $this->assertTrue(strlen($rawData['pulse']) > 4);
    }

    /**
     * @covers ICANS\Component\IcansLoggingComponent\Transport\MessageFactory::createMessageWithPulseId
     */
    public function testCreateMessageWithPulseIdWithExistingHttpHost()
    {

        $oldhost = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null;
        $_SERVER['HTTP_HOST'] = 'my-dummy-hostname';

        $actual = $this->object->createMessageWithPulseId(
            'message type',
            'message handle',
            'message version',
            array(
                 'Body item 1',
                 'Body item 2',
            ),
            'app',
            'origin service type',
            'origin service component',
            'origin service instance',
            'pulse ID',
            1234,
            'log level name'
        );

        // Additionally, when createMessage() is called, a random pulse ID is
        // created. As we cannot predict it and neither can perform any test
        // without relying on internals, we simply verify it's a non-empty
        // string and do nothing more.
        $rawData = $actual->getRawData();

        $this->assertInternalType('string', $rawData['origin_host']);
        $this->assertSame('my-dummy-hostname', $rawData['origin_host']);

        $_SERVER['HTTP_HOST'] = $oldhost; // Reset to initial state
    }

}
