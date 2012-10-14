<?php
/**
 * Implements a filter for a monolog record to identify if a given value is empty
 *
 * @author    Mike Lohmann <mike.lohmann@icans-gmbh.com>
 * @copyright 2011 ICANS GmbH (http://www.icans-gmbh.com)
 */
namespace ICANS\Component\IcansLoggingComponent\Filter;

use ICANS\Component\IcansLoggingComponent\FilterInterface;

/**
 * Filters the record (array of arrays) if a specified value is empty.
 */
class EmptyFilter implements FilterInterface
{
    /**
     * The key to lookup in the record
     *
     * @var array
     */
    protected $keys = array();

    /**
     * Default constructor, $key specifies which array keys are used to find the value that should not be empty
     *
     * @param array $key
     */
    public function __construct(array $keys = array())
    {
        $this->keys = $keys;
    }

    /**
     * {@inheritDoc}
     */
    public function isRecordToBeFiltered(array $record)
    {

        $workingKeys = $this->keys;
        while (($key = array_shift($workingKeys)) !== null) {
            if (!array_key_exists($key, $record)) {
                return false;
            }
            $record = $record[$key];
        }
        return empty($record);
    }
}
