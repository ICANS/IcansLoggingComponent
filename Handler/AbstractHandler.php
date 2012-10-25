<?php
/**
 * Declares the AbstractHandler class.
 *
 * @author    Mike Lohmann <mike.lohmann@icans-gmbh.com>
 * @author    Wolf Bauer <wolf.bauer@icans-gmbh.com>
 * @copyright 2012 ICANS GmbH (http://www.icans-gmbh.com)
 */
namespace ICANS\Component\IcansLoggingComponent\Handler;

use ICANS\Component\IcansLoggingComponent\Api\V1\WriteFilterInterface;
use ICANS\Component\IcansLoggingComponent\Api\V1\HandleFilterInterface;
use ICANS\Component\IcansLoggingComponent\Api\V1\HandlerInterface;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

/**
 * Defines an abstract Handler for LoggingHandlers
 */
abstract class AbstractHandler extends AbstractProcessingHandler implements HandlerInterface
{
    /**
     * @var array
     */
    protected $writeFilters = array();

    /**
     * @var array
     */
    protected $handlingFilters = array();

    /**
     * Flag to enable the Handler to shut itself off on flume errors
     * @var boolean
     */
    protected $handlingStopped = false;

    /**
     * {@inheritDoc}
     */
    public function isHandlingStopped()
    {
        return $this->handlingStopped;
    }

    /**
     * {@inheritDoc}
     */
    public function addWriteFilters(array $filters)
    {
        foreach ($filters as $filter) {
            $this->addWriteFilter($filter);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function addWriteFilter(WriteFilterInterface $filter)
    {
        $this->writeFilters[] = $filter;
    }

    /**
     * {@inheritDoc}
     */
    public function addHandlingFilters(array $filters)
    {
        foreach ($filters as $filter) {
            $this->addHandlingFilter($filter);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function addHandlingFilter(HandleFilterInterface $filter)
    {
        $this->handlingFilters[] = $filter;
    }
}