<?php
/**
 * This file contains the MilliSecondDateTime.
 *
 * @author     Wolf Bauer
 * @copyright  2012 ICANS GmbH (http://www.icans-gmbh.com)
 */
namespace ICANS\Component\IcansLoggingComponent\Transport;

use Datetime;
use DateTimeZone;

/**
 * Definition of the MilliSecondDateTime.
 */
class MilliSecondDateTime extends Datetime
{
    /**
     * @var int
     */
    private $millisecond = 0;

    /**
     * @var int
     */
    private $timeStampInMilliseconds;

    /**
     * @param int          $time
     * @param DateTimeZone $timeZone
     */
    public function __construct($time = null, DateTimeZone $timeZone = null)
    {
        if (is_null($timeZone)) {
            $timeZone = new DateTimeZone('UTC');
        }

        if (is_null($time)) {
            $time = microtime();
        }

        if (is_null($timeZone)) {
            $timeZone = new DateTimeZone('UTC');
        }

        if (preg_match('/@(\\d+)\\.(\\d+)/', $time, $matches)) {
            // store milliseconds with precision 3 and add some zeros to get 8 decimal places
            $this->millisecond = (int) $matches[2];
            $this->millisecond = round($this->millisecond / 10);
            // store timestamp in milliseconds
            $calculationTime               = str_replace('@', '', $time);
            $this->timeStampInMilliseconds = round($calculationTime * 1000);

            // set time to simple timestamp
            $time = '@' . $matches[1];
        } elseif (preg_match('/(\\d+)\\ (\\d+)/', $time, $matches)) {
            // store millisecond part
            $this->millisecond             = substr($matches[1], 0, 8);
            $millisecondsShort             = (int) substr($this->millisecond, 0, 4);
            // set milliseconds timestamp part
            $this->timeStampInMilliseconds = round($millisecondsShort / 10);
            // add normal timestamp
            $this->timeStampInMilliseconds += $matches[2] * 1000;

            // set time to simple timestamp
            $time = '@' . $matches[2];
        }

        parent::__construct($time, $timeZone);
    }

    /**
     * @return string
     */
    public function getDateStringWithMilliseconds()
    {
        return $this->format('Y-m-d\TH:i:s') . '.' . $this->millisecond . $this->format('O');
    }

    /**
     * @return int
     */
    public function getTimestampInMilliseconds()
    {
        if (!empty($this->millisecond)) {
            return $this->timeStampInMilliseconds;
        }

        return parent::getTimestamp() + '00000000';
    }
}
