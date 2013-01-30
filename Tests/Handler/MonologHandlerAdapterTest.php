<?php
/**
 * This file contains the MonologHandlerAdapterTest.
 *
 * @author     Wolf Bauer
 * @copyright  2012 ICANS GmbH (http://www.icans-gmbh.com)
 */
namespace ICANS\Component\IcansLoggingComponent\Handler;

use PHPUnit_Framework_MockObject_MockObject;
use ReflectionMethod;

/**
 * Definition of the MonologHandlerAdapterTest. Contains Unittests for MonologHandlerAdapter.
 *
 * @covers ICANS\Component\IcansLoggingComponent\Handler\MonologHandlerAdapter<extended>
 */
class MonologHandlerAdapterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var MonologHandlerAdapter
     */
    private $subject;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $handlerMock;

        /**
         * {@inheritDoc}
         */
    public function setUp()
    {
        $this->handlerMock = $this->getMockBuilder('Monolog\Handler\AbstractProcessingHandler')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $this->subject = new MonologHandlerAdapter($this->handlerMock);
    }

    /**
     * @covers ICANS\Component\IcansLoggingComponent\Handler\MonologHandlerAdapter::checkIsHandling
     */
    public function testCheckIsHandling()
    {
        $method = new ReflectionMethod($this->subject, 'checkIsHandling');
        $method->setAccessible(true);
        $this->assertTrue($method->invoke($this->subject, array()));
    }

    /**
     * @covers ICANS\Component\IcansLoggingComponent\Handler\MonologHandlerAdapter::handleWrite
     */
    public function testHandleWrite()
    {
        $method = new ReflectionMethod($this->subject, 'handleWrite');
        $method->setAccessible(true);

        $testRecord = array('foo' => 'bar');

        $this->handlerMock->expects($this->once())
            ->method('write')
            ->with($testRecord);

        $method->invoke($this->subject, $testRecord);
    }
}