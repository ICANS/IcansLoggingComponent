<?php
/**
 * Declares the DefaultProgressInformation class.
 *
 * @author    Simon Neidhold (simon.neidhold@icans-gmbh.com)
 * @copyright 2012 ICANS GmbH (http://www.icans-gmbh.com)
 * @version   $Id: $
 */
namespace ICANS\Component\IcansLoggingComponent\Progress;

/**
 * A default progress information container class.
 */
class DefaultProgressInformation implements ProgressInformationInterface
{
    /**
     * @var integer
     */
    private $totalCount;

    /**
     * @var integer
     */
    private $remainingCount;

    /**
     * @var string
     */
    private $previousTask;

    /**
     * @var string
     */
    private $currentTask;

    /**
     * Creates a new progress information container for the given number of tasks.
     *
     * @param integer $totalTaskCount
     *
     * @throws \Exception
     */
    public function __construct($totalTaskCount)
    {
        $totalTaskCount = intval($totalTaskCount);
        if (0 >= $totalTaskCount) {
            throw new \Exception('Total task count must be at least 1!');
        }

        $this->totalCount     = $totalTaskCount;
        $this->remainingCount = $totalTaskCount;
    }

    /**
     * {@inheritDoc}
     */
    public function getTotalTaskCount()
    {
        return $this->totalCount;
    }

    /**
     * {@inheritDoc}
     */
    public function getRemainingTaskCount()
    {
        return $this->remainingCount;
    }

    /**
     * {@inheritDoc}
     */
    public function getCurrentTask()
    {
        return $this->currentTask;
    }

    /**
     * {@inheritDoc}
     */
    public function getPreviousTask()
    {
        return $this->previousTask;
    }

    /**
     * Signals processing start of a task. You may pass an optional task description that is treated as the "current"
     * task.
     *
     * @param string $taskDescription
     */
    public function beginTask($taskDescription = '')
    {
        $this->currentTask = $taskDescription;
    }

    /**
     * Signals finishing of a task decrements the number of tasks remaining. You may pass an optional task description
     * that is treated as the "previous" task.
     *
     * @param string $taskDescription
     */
    public function endTask($taskDescription = '')
    {
        $this->previousTask = $taskDescription;
        $this->currentTask  = '';

        if (0 < $this->remainingCount) {
            --$this->remainingCount;
        }
    }
}
