<?php
/**
 * Declares the FlumeThriftHandler class.
 *
 * @author    Mike Lohmann <mike.lohmann@icans-gmbh.com>
 * @author    sebastian.pleschko
 * @copyright 2011 ICANS GmbH (http://www.icans-gmbh.com)
 */
namespace Icans\Ecf\Component\Logging;

use ICANS\Component\IcansLoggingComponent\FilterInterface;
use ICANS\Component\IcansLoggingComponent\Flume\Server AS Flume;
use ICANS\Component\IcansLoggingComponent\Handler\ThriftFlumeProcessingHandler;

use Monolog\Logger;

use Thrift AS Thrift;

/**
 * Test for the monolog handler for the flume connection
 *
 * @author    Mike Lohmann <mike.lohmann@icans-gmbh.com>
 * @author    sebastian.pleschko
 * @copyright 2011 ICANS GmbH (http://www.icans-gmbh.com)
 */
class ThriftFlumeProcessingHandlerTest extends \PHPUnit_Framework_TestCase
{

    protected $flumeClientMock;

    protected $thriftTTransportMock;

    /**
     * Set up the mocks
     */
    protected function setUp()
    {
        $this->flumeClientMock = $this->getMockBuilder('ICANS\Component\IcansLoggingComponent\Flume\ThriftFlumeEventServerClient')
            ->disableOriginalConstructor()->setMethods(array('close', 'append'))
            ->getMock();

        $this->thriftTTransportMock = $this->getMockBuilder('Thrift\Transport\TTransport')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     *  Test if the construction of the handler works as expected.
     */
    public function testConstructTransportOpenFailure()
    {
        $this->thriftTTransportMock
            ->expects($this->once())
            ->method('open')
            ->will($this->throwException(new Thrift\Exception\TException()));

        // Called by Processor->_destruct()
        $this->flumeClientMock
            ->expects($this->once())
            ->method('close');

        $thriftFlumeProcessingHandler = new ThriftFlumeProcessingHandler($this->thriftTTransportMock,
            $this->flumeClientMock);

        $this->assertTrue($thriftFlumeProcessingHandler->isHandlingStopped());
        $this->assertFalse($thriftFlumeProcessingHandler->isHandling(array()));
    }

    /**
     * Test if filters are taken into consideration
     */
    public function testIsHandlingFiltersFalse()
    {
        $filterMock = $this->getMockBuilder('ICANS\Component\IcansLoggingComponent\FilterInterface')
            ->disableOriginalConstructor()
            ->setMethods(array('isRecordToBeFiltered'))
            ->getMock();

        $filterMock->expects($this->once())
            ->method('isRecordToBeFiltered')
            ->will($this->returnValue(true));

        $thriftFlumeProcessingHandler = new ThriftFlumeProcessingHandler($this->thriftTTransportMock,
            $this->flumeClientMock);

        $thriftFlumeProcessingHandler->addFilter($filterMock);

        $this->assertFalse($thriftFlumeProcessingHandler->isHandling(array()));
    }

    /**
     * Test if handler handles debug level
     */
    public function testIsHandlingTrue()
    {
        $thriftFlumeProcessingHandler = new ThriftFlumeProcessingHandler($this->thriftTTransportMock,
            $this->flumeClientMock);

        $this->assertTrue($thriftFlumeProcessingHandler->isHandling(array('level' => Logger::DEBUG)));
    }

    /**
     * test the close method which should be called upon object destruction
     */
    public function testClose()
    {
        $this->flumeClientMock
            ->expects($this->once())
            ->method('close');


        $thriftFlumeProcessingHandler = new ThriftFlumeProcessingHandler($this->thriftTTransportMock,
            $this->flumeClientMock);

        $this->assertInstanceOf('ICANS\Component\IcansLoggingComponent\Flume\ThriftFlumeEventServerClient',
            \PHPUnit_Framework_Assert::readAttribute($thriftFlumeProcessingHandler, 'client'));


        $this->assertFalse($thriftFlumeProcessingHandler->isHandlingStopped());
    }

    /**
     * test the close method which should be called upon object destruction
     */
    public function testCloseException()
    {
        $this->flumeClientMock
            ->expects($this->once())
            ->method('close')
            ->will($this->throwException(new \Exception('test')));

        $thriftFlumeProcessingHandler = new ThriftFlumeProcessingHandler($this->thriftTTransportMock,
            $this->flumeClientMock);

        $this->assertInstanceOf('ICANS\Component\IcansLoggingComponent\Flume\ThriftFlumeEventServerClient',
            \PHPUnit_Framework_Assert::readAttribute($thriftFlumeProcessingHandler, 'client'));

        $thriftFlumeProcessingHandler->close();
        $this->assertTrue($thriftFlumeProcessingHandler->isHandlingStopped());
    }

    /**
     * Test writing wrong parameters
     * @expectedException PHPUnit_Framework_Error
     */
    public function testWriteWrongParameter()
    {
        $thriftFlumeProcessingHandler = new ThriftFlumeProcessingHandler($this->thriftTTransportMock,
            $this->flumeClientMock);

        $thriftFlumeProcessingHandler->write('test');
    }

    /**
     * Test writing to flume
     */
    public function testWrite()
    {
        $this->flumeClientMock
            ->expects($this->once())
            ->method('append');

        $thriftFlumeProcessingHandler = new ThriftFlumeProcessingHandler($this->thriftTTransportMock,
            $this->flumeClientMock);

        $thriftFlumeProcessingHandler->write(array('test'));
    }

    /**
     * Test if filters are taken into consideration
     */
    public function testAddFilters()
    {
        $filterMock1 = $this->getMockBuilder('ICANS\Component\IcansLoggingComponent\FilterInterface')
            ->disableOriginalConstructor()
            ->setMethods(array('isRecordToBeFiltered'))
            ->getMock();

        $filterMock1->expects($this->once())
            ->method('isRecordToBeFiltered')
            ->will($this->returnValue(false));

        $filterMock2 = $this->getMockBuilder('ICANS\Component\IcansLoggingComponent\FilterInterface')
            ->disableOriginalConstructor()
            ->setMethods(array('isRecordToBeFiltered'))
            ->getMock();

        $filterMock2->expects($this->once())
            ->method('isRecordToBeFiltered')
            ->will($this->returnValue(false));

        $thriftFlumeProcessingHandler = new ThriftFlumeProcessingHandler($this->thriftTTransportMock,
            $this->flumeClientMock);

        $thriftFlumeProcessingHandler->addFilters(array($filterMock1, $filterMock2));

        // Inherently checks filter by calling isHanding
        $this->assertTrue($thriftFlumeProcessingHandler->isHandling(array('level' => Logger::ERROR)));
    }
}