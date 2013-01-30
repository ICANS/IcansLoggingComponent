<?php
/**
 * This file contains the MonologHandlerAdapter.
 *
 * @author     Wolf Bauer
 * @copyright  2012 ICANS GmbH (http://www.icans-gmbh.com)
 */
namespace ICANS\Component\IcansLoggingComponent\Handler;

use Monolog\Handler\AbstractProcessingHandler;

/**
 * Definition of the MonologHandlerAdapter. With this adapter every AbstractProcessingHandler monolog handler can be
 * used as "ICANS\Component\IcansLoggingComponent\Api\V1\HandlerInterface"
 */
class MonologHandlerAdapter extends AbstractHandler
{
    /**
     * @var AbstractProcessingHandler
     */
    private $monologHandler;

    /**
     * @param AbstractProcessingHandler $monologHandler
     */
    public function __construct(AbstractProcessingHandler $monologHandler)
    {
        $this->monologHandler = $monologHandler;
    }

    /**
     * {@inheritDoc}
     */
    protected function checkIsHandling(array $record)
    {
        return $this->monologHandler !== null;
    }

    /**
     * {@inheritDoc}
     */
    protected function handleWrite(array $record)
    {
        $this->monologHandler->write($record);
    }
}
