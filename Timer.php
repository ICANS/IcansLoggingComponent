<?php
/**
 * Declares the Timer class.
 *
 * @author    Simon Neidhold (simon.neidhold@icans-gmbh.com)
 * @copyright 2012 ICANS GmbH (http://www.icans-gmbh.com)
 * @version   $Id: $
 */
namespace ICANS\Component\IcansLoggingComponent;

/**
 * A simple timer used to measure spent time during code execution.
 */
class Timer
{
    const KEY_TIME = 't';
    const KEY_NOTE = 'n';

    /**
     * @var array
     */
    private $timingPoints;

    /**
     * Sets up a new timer instance.
     */
    public function __construct()
    {
        $this->timingPoints = array();
    }

    /**
     * Adds a new timing point to this timer. A optional note may be passed for later reference.
     *
     * @param string $note [optional]
     */
    public function addTimingPoint($note = '')
    {
        $now = microtime(true);
        $this->timingPoints[] = array(
            self::KEY_TIME => $now,
            self::KEY_NOTE => $note
        );
    }

    /**
     * Returns the milliseconds spent between the latest and its previous timing point. The optional precision
     * parameter specifies the number of floating points to be included.
     *
     * @param integer $precision [optional]
     *
     * @return float
     */
    public function getLatestTimeframe($precision = 2)
    {
        $timeFrame = 0;

        $lastIndex = count($this->timingPoints) - 1;
        if (1 <= $lastIndex) {
            $previousRecord = $this->timingPoints[$lastIndex - 1];
            $latestRecord   = $this->timingPoints[$lastIndex];

            $timeFrame = $this->calculateTimeframe($previousRecord, $latestRecord, $precision);
        }

        return $timeFrame;
    }

    /**
     * Returns the milliseconds spent between the first and the last timing point for this timer. The optional precision
     * parameter specifies the number of floating points to be included.
     *
     * @param integer $precision [optional]
     *
     * @return float
     */
    public function getTotalTimeframe($precision = 2)
    {
        $totalTime = 0;

        if (2 <= count($this->timingPoints)) {
            $startRecord = reset($this->timingPoints);
            $endRecord   = end($this->timingPoints);

            $totalTime = $this->calculateTimeframe($startRecord, $endRecord, $precision);
        }

        return $totalTime;
    }

    /**
     * Returns the time spent between given start- and end-record in milliseconds. The precision-parameter specifies
     * the precision in terms of floating-points included.
     *
     * @param array   $startRecord
     * @param array   $endRecord
     * @param integer $precision
     *
     * @return float
     */
    private function calculateTimeframe(array $startRecord, array $endRecord, $precision)
    {
        $tStart = $startRecord[self::KEY_TIME];
        $tEnd   = $endRecord[self::KEY_TIME];

        $tSpent = $tEnd - $tStart;
        return round(1000 * $tSpent, $precision);
    }
}
