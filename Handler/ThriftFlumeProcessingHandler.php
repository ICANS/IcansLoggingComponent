<?php
/**
 * Declares the FlumeThriftHandler class.
 *
 * @author    Mike Lohmann <mike.lohmann@icans-gmbh.com>
 * @author    Sebastian Latza
 * @copyright 2011 ICANS GmbH (http://www.icans-gmbh.com)
 */
namespace ICANS\Component\IcansLoggingComponent\Handler;

use ICANS\Component\IcansLoggingComponent\Handler\AbstractHandler;
use ICANS\Component\IcansLoggingComponent\Flume\Priority;
use ICANS\Component\IcansLoggingComponent\Flume\ThriftFlumeEvent;
use ICANS\Component\IcansLoggingComponent\Flume\ThriftFlumeEventServerClient;

use Monolog\Logger;

use Thrift AS Thrift;

/**
 * FlumeHandler bridges log messages between monolog and flume
 */
class ThriftFlumeProcessingHandler extends AbstractHandler
{
    /**
     * @var ThriftFlumeEventServerClient
     */
    private $client;

    /**
     * Default constructor
     *
     * @param Thrift\Transport\TTransport   $flumeTransport
     * @param ThriftFlumeEventServerClient  $client
     * @param int                           $level The minimum logging level at which this handler will be triggered
     * @param Boolean                       $bubble Whether the messages that are handled can bubble up the stack or not
     *
     * @SuppressWarnings(PMD.UnusedLocalVariable) Exception needs to be caught, but can't be used
     */
    public function __construct(
        Thrift\Transport\TTransport $flumeTransport,
        ThriftFlumeEventServerClient $client,
        $level = Logger::DEBUG,
        $bubble = true
    )
    {
        $this->client = $client;

        /*
        * Even if flume is away, the application should work. Thats why the exception is catched.
        */
        try {
            $flumeTransport->open();
        } catch (Thrift\Exception\TException $thriftException) {
            $this->handlingStopped = true;
        }
        parent::__construct($level, $bubble);
    }

    /**
     * {@inheritDoc}
     */
    public function isHandling(array $record)
    {
        if (true === $this->isHandlingStopped()) {
            return false;
        }

        if (!empty($this->handlingFilters)) {
            foreach ($this->handlingFilters as $filter) {
                if (true === $filter->isRecordToBeHandled($record)) {
                    return false;
                }
            }
        }

        return parent::isHandling($record);
    }

    /**
     * {@inheritDoc}
     */
    public function close()
    {
        if (!empty($this->client)) {
            try {
                $this->client->close();
            } catch (\Exception $exception) {
                // Do nothing. Errors during closing of Flume should not bother the app. Also, it seems quite hard to log them ...
                // regarding to log everything to flume aspect
                // @todo there should be an external monitoring of this flume-zombie-state which should trigger warnings
                // in zabbix and kick the app-server out of the loadbalancer
                $this->handlingStopped = true;
                unset($this->client);
            }
        }
    }


    /**
     * Matches the Monolog loglevel to the corresponding flume equivalent. Can be used to set
     * the priority of the FlumeEvent. For example if you extend this handler you could overwrite
     * the write method and add the priority to the FlumeEvent:
     *
     * $mappedLogLevel = $this->mapLoggerLogLevelToFlumePriority($record['message_loglevel_value']);
     * $event = new Flume\ThriftFlumeEvent(array(
     *                                          'priority' => $mappedLogLevel,
     *                                          'timestamp' => $timestamp,
     *                                          'host' => $hostName,
     *                                          'body' => $record['formatted']
     *                                          ));
     *
     * @param string $loglevel
     *
     * @return int
     */
    protected function mapLoggerLogLevelToFlumePriority($loglevel)
    {
        switch ($loglevel) {
            case Logger::ALERT:
                return Priority::FATAL;
                break;
            case Logger::CRITICAL:
                return Priority::ERROR;
                break;
            case Logger::ERROR:
                return Priority::ERROR;
                break;
            case Logger::WARNING:
                return Priority::WARN;
                break;
            case Logger::INFO:
                return Priority::INFO;
                break;
            case Logger::DEBUG:
                return Priority::DEBUG;
                break;
            default:
                return Priority::ERROR;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function write(array $record)
    {
        if (!empty($this->writeFilters)) {
            foreach ($this->writeFilters as $filter) {
                if (true === $filter->isRecordToBeFiltered($record)) {
                    $record = $filter->filterRecord();
                }
            }
        }

        $event = new ThriftFlumeEvent($record);
        $this->sendThriftFlumeEvent($event);
    }

    /**
     * sends the event (and its data) to the flume node
     *
     * @param ThriftFlumeEvent $event
     */
    protected function sendThriftFlumeEvent(ThriftFlumeEvent $event)
    {
        if (!empty($this->client)) {
            $this->client->append($event);
        }
    }

}
