<?php
/**
 * Declares the EmptyFilter class.
 *
 * @author    Mike Lohmann <mike.lohmann@icans-gmbh.com>
 * @author    Malte Stenzel
 * @copyright 2012 ICANS GmbH (http://www.icans-gmbh.com)
 */
namespace ICANS\Component\IcansLoggingComponent\Tests\Filter;

use ICANS\Component\IcansLoggingComponent\Filter\EmptyFilter;

/**
 * Tests the EmptyFilter implementation used to check in a multi dimensional array for emptiness of a value for a given
 * combination of keys
 *
 * @author    Mike Lohmann <mike.lohmann@icans-gmbh.com>
 * @author    Malte Stenzel
 * @copyright 2012 ICANS GmbH (http://www.icans-gmbh.com)
 */
class EmptyFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests if filter find empty values in given record array for given key array
     * @dataProvider testFilterEmptyTrueDataProvider
     */
    public function testFilterEmptyTrue(array $keys, array $record) {
        $emptyFilter = new EmptyFilter($keys);
        $this->assertTrue($emptyFilter->isRecordToBeFiltered($record));
    }

    /**
     * Data Provider for testFilterEmptyTrue
     * @return array
     */
    public function testFilterEmptyTrueDataProvider() {
        return array(
            // Testlauf
            array(
                array('fooKey'),
                array('bar' => 'baz', 'fooKey' => ''),
            ),
            array(
                array('fooKey', 'barKey'),
                array('bar' => 'baz', 'fooKey' => array('barKey' => null)),
            ),
        );
    }

    /**
     * Tests if filter returns false if no empty values in given record array for given key array are found
     * @dataProvider testFilterEmptyFalseDataProvider
     */
    public function testFilterEmptyFalse(array $keys, array $record) {
        $emptyFilter = new EmptyFilter($keys);
        $this->assertFalse($emptyFilter->isRecordToBeFiltered($record));
    }

    /**
     * Data Provider for testFilterEmptyFalse
     * @return array
     */
    public function testFilterEmptyFalseDataProvider() {
        return array(
            // Testlauf
            array(
                array('fooKey'),
                array('bar' => 'baz', 'fooKey' => new \DateTime),
            ),
            array(
                array('fooKey'),
                array('bar' => 'baz', 'fooKey' => 1337),
            ),
            array(
                array('fooKey', 'barKey'),
                array('bar' => 'baz', 'fooKey' => array('test' => 1, 'barKey' => 'asasas')),
            ),
            array( // Key not found => false
                array('fooKey', 'barKey'),
                array(),
            ),
            array( // Key not found => false
                array('fooKey'),
                array('bar' => 'baz', 'testDT' => new \DateTime()),
            ),
        );
    }

}