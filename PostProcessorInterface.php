<?php
/**
 * This file contains the interface of the analytics post processor to append same data to different events you want
 * to log. It enriches the log body with some global request data.
 *
 * PHP Version 5.3
 *
 * @author    Mike Lohmann <mike.lohmann@icans-gmbh.com>
 * @copyright 2012 ICANS GmbH
 */
namespace ICANS\Component\IcansLoggingComponent;

/**
 * This class defines the common methods needs to be implemented to provide the post processing.
 *
 * @author    Mike Lohmann <mike.lohmann@icans-gmbh.com>
 * @copyright 2011 ICANS GmbH
 */
interface PostProcessorInterface
{
    /**
     * This function adds some additional information to the 'extra' field
     * in the logging body.
     *
     * @param array $record The record created by monolog
     *
     * @return array
     */
    public function processRecord(array $record);

    /**
     * Invoke magic method which is called by monolog
     *
     * @param array $record
     *
     * @return mixed
     */
    public function __invoke($record);


}