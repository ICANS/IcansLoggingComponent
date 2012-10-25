<?php
/**
 * This file contains the interface for additional methods on the Monolog/Handler
 *
 * PHP Version 5.3
 *
 * @author    Mike Lohmann <mike.lohmann@icans-gmbh.com>
 * @copyright 2012 ICANS GmbH
 */
namespace ICANS\Component\IcansLoggingComponent\Api\V1;

use ICANS\Component\IcansLoggingComponent\Api\V1\WriteFilterInterface;
use ICANS\Component\IcansLoggingComponent\Api\V1\HandleFilterInterface;

/**
 * This class defines the methods needs to be implemented for an ICANS handler
 *
 * @author    Mike Lohmann <mike.lohmann@icans-gmbh.com>
 * @copyright 2011 ICANS GmbH
 */
interface HandlerInterface
{
    /**
     * Adds an array of WriteFilterInterface to a Handler.
     *
     * @param WriteFilterInterface $filter
     */
    public function addWriteFilter(WriteFilterInterface $filter);

    /**
     * Adds an array of WriteFilterInterface to this Handler to filter out records during write.
     *
     * @param array $filters
     */
    public function addWriteFilters(array $filters);

    /**
     * Handling filters are used to check if the handler should handle the record.
     * Add FilterInterface to a Handler.
     *
     * @param HandleFilterInterface $filter
     */
    public function addHandlingFilter(HandleFilterInterface $filter);

    /**
     * Adds an array of HandleFilterInterface to a Handler.
     *
     * @param array $filters
     */
    public function addHandlingFilters(array $filters);

    /**
     * @return bool
     */
    public function isHandlingStopped();

}