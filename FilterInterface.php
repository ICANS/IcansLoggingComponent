<?php
/**
 * This file contains the interface to decide if a record will be handled or not
 *
 * PHP Version 5.3
 *
 * @author    Mike Lohmann <mike.lohmann@icans-gmbh.com>
 * @copyright 2012 ICANS GmbH
 */
namespace ICANS\Component\IcansLoggingComponent;

/**
 * This class defines the common methods needs to be implemented to filter out if event is processed
 *
 * @author    Mike Lohmann <mike.lohmann@icans-gmbh.com>
 * @copyright 2011 ICANS GmbH
 */
interface FilterInterface
{
    /**
     * This function filters the record to decide if record will be handled or not
     *
     * @param array $record The record created by monolog
     *
     * @return Boolean
     */
    public function isRecordToBeFiltered(array $record);

    /**
     * This function filters the record and return a filtered record
     *
     * @param array $record The record created by monolog
     *
     * @return array
     */
    public function filterRecord(array $record);
}