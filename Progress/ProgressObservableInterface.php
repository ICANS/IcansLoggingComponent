<?php
/**
 * Declares the ProgressObservableInterface interface.
 *
 * @author    Simon Neidhold (simon.neidhold@icans-gmbh.com)
 * @copyright 2012 ICANS GmbH (http://www.icans-gmbh.com)
 * @version   $Id: $
 */
namespace ICANS\Component\IcansLoggingComponent\Progress;

/**
 * Interface to be implemented by observables providing progress information.
 */
interface ProgressObservableInterface
{
    /**
     * Attaches the given observer to be notified upon future progress changes.
     *
     * @param ProgressObserverInterface $observer
     */
    public function attach(ProgressObserverInterface $observer);

    /**
     * Detaches the given observer from the list of observer to be notified upon progress changes.
     *
     * @param ProgressObserverInterface $observer
     */
    public function detach(ProgressObserverInterface $observer);

    /**
     * Returns the current progress information.
     *
     * @return ProgressInformationInterface
     */
    public function getProgressInformation();
}
