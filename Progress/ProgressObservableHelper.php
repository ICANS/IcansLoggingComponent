<?php
/**
 * Declares the ProgressObservableHelper class.
 *
 * @author    Simon Neidhold (simon.neidhold@icans-gmbh.com)
 * @copyright 2012 ICANS GmbH (http://www.icans-gmbh.com)
 * @version   $Id: $
 */
namespace ICANS\Component\IcansLoggingComponent\Progress;

/**
 *
 */
class ProgressObservableHelper implements ProgressObservableInterface
{
    /**
     * @var array
     */
    private $observers;

    /**
     * @var ProgressObservableInterface
     */
    private $observable;

    /**
     * @var DefaultProgressInformation
     */
    private $progressInfo;

    /**
     *
     */
    public function __construct(ProgressObservableInterface $observable)
    {
        $this->observers  = array();
        $this->observable = $observable;
    }

    /**
     * {@inheritDoc}
     */
    public function attach(ProgressObserverInterface $observer)
    {
        if (!in_array($observer, $this->observers)) {
            $this->observers[] = $observer;
        }
    }

    /**
     * Detaches the given observer from the list of observer to be notified upon progress changes.
     *
     * @param ProgressObserverInterface $observer
     */
    public function detach(ProgressObserverInterface $observer)
    {
        $observerIndex = array_search($observer, $this->observers);
        if (false !== $observer) {
            unset($this->observers[$observerIndex]);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getProgressInformation()
    {
        return $this->progressInfo;
    }

    /**
     * @param integer $totalTaskCount
     */
    public function createProgressInformation($totalTaskCount)
    {
        $this->progressInfo = new DefaultProgressInformation($totalTaskCount);
    }

    /**
     * @param string $taskDescription
     */
    public function beginTask($taskDescription = '')
    {
        if (isset($this->progressInfo)) {
            $this->progressInfo->beginTask($taskDescription);
            $this->notifyObservers();
        }
    }

    /**
     * @param string $taskDescription
     */
    public function endTask($taskDescription = '')
    {
        if (isset($this->progressInfo)) {
            $this->progressInfo->endTask($taskDescription);
            $this->notifyObservers();
        }
    }

    /**
     *
     */
    private function notifyObservers()
    {
        foreach ($this->observers as $observer) {
            /* @var $observer ProgressObserverInterface */
            $observer->progressChanged($this->observable);
        }
    }
}
