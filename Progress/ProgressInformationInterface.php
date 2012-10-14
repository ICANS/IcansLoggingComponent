<?php
/**
 * Declares the ProgressInformationInterface interface.
 *
 * @author    Simon Neidhold (simon.neidhold@icans-gmbh.com)
 * @copyright 2012 ICANS GmbH (http://www.icans-gmbh.com)
 * @version   $Id: $
 */
namespace ICANS\Component\IcansLoggingComponent\Progress;

/**
 * Interface for classes representing a certain progress.
 */
interface ProgressInformationInterface
{
    /**
     * Returns the total number of tasks to be processed.
     *
     * @return integer
     */
    public function getTotalTaskCount();

    /**
     * Returns the remaining number of tasks to be processed.
     *
     * @return integer
     */
    public function getRemainingTaskCount();

    /**
     * Returns the description of the task currently processed. In case no task is currently processed, an empty string
     * is returned.
     *
     * @return string
     */
    public function getCurrentTask();

    /**
     * Returns the description of the task previously processed. In case no task was previously processed, an empty
     * string is returned.
     *
     * @return string
     */
    public function getPreviousTask();
}
