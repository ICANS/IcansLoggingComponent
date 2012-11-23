<?php
/**
 * This file contains the MessageInterface.
 *
 * @author     Wolf Bauer
 * @copyright  2012 ICANS GmbH (http://www.icans-gmbh.com)
 */
namespace ICANS\Component\IcansLoggingComponent\Api\V1;

/**
 * Definition of the MessageInterface.
 */
interface MessageInterface
{
    /**
     * Storage key for the pulse property.
     */
    const PULSE_ID = 'pulse';

    /**
     * Storage key for the body property.
     */
    const BODY = 'event_body';

    /**
     * Storage key for the event_type property.
     */
    const TYPE = 'event_type';

    /**
     * Storage key for the event handle value property.
     */
    const HANDLE = 'event_handle';

    /**
     * Storage key for the event version value property.
     */
    const VERSION = 'event_version';

    /**
     * Storage key for the creation timestamp property.
     */
    const CREATED_TIMESTAMP = 'created_timestamp';

    /**
     * Storage key for the creation date property.
     */
    const CREATED_DATE = 'created_date';

    /**
     * Storage key for the envelope version property.
     */
    const ENVELOPE_VERSION = 'envelope_version';

    /**
     * Storage key for the origin host property.
     */
    const ORIGIN_HOST = 'origin_host';

    /**
     * Storage key for the origin type property.
     */
    const ORIGIN_TYPE = 'origin_type';

    /**
     * Storage key for the origin service type property.
     */
    const ORIGIN_SERVICE_TYPE = 'origin_service_type';

    /**
     * Storage key for the origin service component property.
     */
    const ORIGIN_SERVICE_COMPONENT = 'origin_service_component';

    /**
     * Storage key for the origin_service_instance property.
     */
    const ORIGIN_SERVICE_INSTANCE = 'origin_service_instance';

    /**
     * Storage key for the message loglevel value property.
     */
    const LOGLEVEL_VALUE = 'message_loglevel_value';
    
    /**
     * Propriate loglevel name.
     */
    const PROPRIATE_LOGLEVEL_VALUE = 'level';    


    /**
     * Storage key for the message loglevel property.
     */
    const LOGLEVEL = 'message_loglevel';

    /**
     * @return array
     */
    public function getRawData();
}
