<?php
/**
 * This file contains the AbstractTransportObject.
 *
 * @author     Wolf Bauer
 * @copyright  2012 ICANS GmbH (http://www.icans-gmbh.com)
 * @version    $Id: $
 */
namespace ICANS\Component\IcansLoggingComponent\Transport;

use \InvalidArgumentException;

/**
 * Definition of the AbstractTransportObject.
 */
abstract class AbstractTransportObject
{
    /**
     * @var array
     */
    protected $rawData = array();

    /**
     * @param array $rawData
     */
    public function __construct(array $rawData = array())
    {
        $this->rawData = $rawData;
    }

    /**
     * Get the value for the requested key from the raw data. Returns an empty string if the
     * key is not found
     *
     * @param string $key
     *
     * @return string
     */
    protected function getStringValue($key)
    {
        if (array_key_exists($key, $this->rawData)) {
            $value = $this->rawData[$key];
            if (is_string($value)) {
                return $value;
            }
        }
        return '';
    }

    /**
     * Set a string value for the given key.
     *
     * @param string $key
     * @param string $value
     *
     * @throws InvalidArgumentException
     */
    protected function setStringValue($key, $value)
    {
        if (is_string($value)) {
            $this->rawData[$key] = $value;
        } else {
            throw new InvalidArgumentException('The value for ' . $key . ' is not a string.');
        }
    }

    /**
     * Get the value for the requested key from the raw data. Returns an empty array if the
     * key is not found.
     *
     * @param string $key
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    protected function getArrayValue($key)
    {
        if (array_key_exists($key, $this->rawData)) {
            $value = $this->rawData[$key];
            if (is_array($value)) {
                return $value;
            }
        }
        return array();
    }

    /**
     * Set a string value for the given key.
     *
     * @param string $key
     * @param string $value
     *
     * @throws \InvalidArgumentException
     */
    protected function setArrayValue($key, $value)
    {
        if (is_array($value)) {
            $this->rawData[$key] = $value;
        } else {
            throw new InvalidArgumentException('The value for ' . $key . ' is not an array.');
        }
    }

    /**
     * Get the value for the requested key from the raw data. Returns 0 if the
     * key is not found
     *
     * @param string $key
     *
     * @return int
     */
    protected function getIntegerValue($key)
    {
        if (array_key_exists($key, $this->rawData)) {
            $value = $this->rawData[$key];
            if (strval($value) === strval((int) $value)) {
                return (int) $value;
            }
        }
        return 0;
    }

    /**
     * Get the value for the requested key from the raw data. Returns 0 if the
     * key is not found
     *
     * @param string $key
     *
     * @return int
     */
    protected function getDoubleValue($key)
    {
        if (array_key_exists($key, $this->rawData)) {
            $value = $this->rawData[$key];
            if (is_double($value)) {
                return (double) $value;
            }
        }
        return 0;
    }

    /**
     * Set a int value for the given key.
     *
     * @param string $key
     * @param int    $value
     *
     * @throws InvalidArgumentException
     */
    protected function setIntegerValue($key, $value)
    {
        if (strval($value) === strval((int) $value)) {
            $this->rawData[$key] = (int) $value;
        } else {
            throw new InvalidArgumentException('The value for ' . $key . ' is not an int.');
        }
    }

    /**
     * Set a double value for the given key.
     *
     * @param string $key
     * @param string $value
     *
     * @throws InvalidArgumentException
     */
    protected function setDoubleValue($key, $value)
    {
        if (strval($value) === strval((double) $value)) {
            $this->rawData[$key] = (double) $value;
        } else {
            throw new InvalidArgumentException('The value for ' . $key . ' is not a double.');
        }
    }

    /**
     * Get the value for the requested key from the raw data. Returns false if the
     * key is not found.
     *
     * @param string $key
     *
     * @return bool
     */
    protected function getBooleanValue($key)
    {
        if (array_key_exists($key, $this->rawData)) {
            $value = $this->rawData[$key];
            if (is_bool($value)) {
                return $value;
            }
        }
        return false;
    }

    /**
     * Set a bool value for the given key.
     *
     * @param string $key
     * @param bool   $value
     *
     * @throws InvalidArgumentException
     */
    protected function setBooleanValue($key, $value)
    {
        if (is_bool($value)) {
            $this->rawData[$key] = $value;
        } else {
            throw new InvalidArgumentException('The value for ' . $key . ' is not a boolean.');
        }
    }
}
