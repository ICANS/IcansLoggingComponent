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
     * @var WriteFilterInterface[]
     */
    protected $writeFilters = array();

    /**
     * @var HandleFilterInterface[]
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
    public function isHandling(array $record)
    {
        if (false === $this->checkIsHandling($record)) {
            return false;
        }

        if (true === $this->isHandlingStopped()) {
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

        $this->handleWrite($record);
    }

    /**
     * Return if the implementation can handle the given record. If the result is false. Additional checks like
     * handling filter, log level are checks if this handler can handle the record.
     *
     * @param array $record
     *
     * @return bool
     */
    protected abstract function checkIsHandling(array $record);

    /**
     * Handle write to log. The $record is already filtered by write filters.
     *
     * @param array $record
     */
    protected abstract function handleWrite(array $record);
}