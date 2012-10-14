<?php
/**
 * Declares the ProgressObserverInterface interface.
 *
 * @author    Simon Neidhold (simon.neidhold@icans-gmbh.com)
 * @copyright 2012 ICANS GmbH (http://www.icans-gmbh.com)
 * @version   $Id: $
 */
namespace ICANS\Component\IcansLoggingComponent\Progress;

/**
 * Interface for classes observing a certain a ProgressObservable.
 */
interface ProgressObserverInterface
{
    /**
     * Called whenever the progress on the given observable has changed.
     *
     * @param ProgressObservableInterface $observable
     */
    public function progressChanged(ProgressObservableInterface $observable);
}
