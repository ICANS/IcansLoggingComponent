<?php
/**
 * This file contains the interface for additional methods on the Monolog/Handler
 *
 * PHP Version 5.3
 *
 * @author    Mike Lohmann <mike.lohmann@icans-gmbh.com>
 * @copyright 2012 ICANS GmbH
 */
namespace ICANS\Component\IcansLoggingComponent;

use ICANS\Component\IcansLoggingComponent\FilterInterface;

/**
 * This class defines the methods needs to be implemented for an ICANS handler
 *
 * @author    Mike Lohmann <mike.lohmann@icans-gmbh.com>
 * @copyright 2011 ICANS GmbH
 */
interface HandlerInterface
{
    /**
     * Adds an array of FilterInterfaces to a Handler.
     *
     * @param FilterInterface $filter
     */
    public function addWriteFilter(FilterInterface $filter);

    /**
     * Adds an array of FilterInterface to this Handler to filter out records during write.
     *
     * @param array $filters
     */
    public function addWriteFilters(array $filters);

    /**
     * Handling filters are used to check if the handler should handle the record.
     * Add FilterInterface to a Handler.
     *
     * @param FilterInterface $filter
     */
    public function addHandlingFilter(FilterInterface $filter);

    /**
     * Adds an array of FilterInterfaces to a Handler.
     *
     * @param array $filters
     */
    public function addHandlingFilters(array $filters);

    /**
     * @return bool
     */
    public function isHandlingStopped();

}