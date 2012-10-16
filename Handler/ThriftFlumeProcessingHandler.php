<?php
/**
 * Declares the FlumeThriftHandler class.
 *
 * @author    Mike Lohmann <mike.lohmann@icans-gmbh.com>
 * @author    Sebastian Latza
 * @copyright 2011 ICANS GmbH (http://www.icans-gmbh.com)
 */
namespace ICANS\Component\IcansLoggingComponent\Handler;

use ICANS\Component\IcansLoggingComponent\FilterInterface;
use ICANS\Component\IcansLoggingComponent\Flume\Priority;
use ICANS\Component\IcansLoggingComponent\Flume\ThriftFlumeEvent;
use ICANS\Component\IcansLoggingComponent\Flume\ThriftFlumeEventServerClient;
use ICANS\Component\IcansLoggingComponent\HandlerInterface;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

use Thrift AS Thrift;

/**
 * FlumeHandler bridges log messages between monolog and flume
 */
class ThriftFlumeProcessingHandler extends AbstractProcessingHandler implements HandlerInterface
{
    /**
     * @var array
     */
    private $writeFilters = array();

    /**
     * @var array
     */
    private $handlingFilters = array();

    /**
     * @var ThriftFlumeEventServerClient
     */
    private $client;

    /**
     * Flag to enable the Handler to shut itself off on flume errors
     * @var boolean
     */
    private $handlingStopped = false;

    /**
     * Default constructor
     *
     * @param integer     $level The minimum logging level at which this handler will be triggered
     * @param Boolean     $bubble Whether the messages that are handled can bubble up the stack or not
     * @param ThriftClientFactory $clientFactory The Factory from which we get out connection
     *
     * @SuppressWarnings(PMD.UnusedLocalVariable) Exception needs to be caught, but can't be used
     */
    public function __construct(Thrift\Transport\TTransport $flumeTransport,
                                ThriftFlumeEventServerClient $client,
                                $level = Logger::DEBUG, $bubble = true)
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
     * @inheritDoc
     */
    public function isHandling(array $record)
    {
        if (true === $this->isHandlingStopped()) {
            return false;
        }

        if (!empty($this->handlingFilters)) {
            foreach ($this->handlingFilters as $filter) {
                if (true === $filter->isRecordToBeFiltered($record)) {
                    return false;
                }
            }
        }

        return parent::isHandling($record);
    }

    /**
     * Called on object destruction. We use this to close the flume connection
     *
     * @return void
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

    /**
     * {@inheritDoc}
     */
    public function isHandlingStopped()
    {
        return $this->handlingStopped;
    }

    /**
     * {@inheritDoc}
     */
    public function addWriteFilters(array $filters)
    {
        foreach ($filters as $filter) {
            $this->addWriteFilter($filter);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function addWriteFilter(FilterInterface $filter)
    {
        $this->writeFilters[] = $filter;
    }

    /**
     * {@inheritDoc}
     */
    public function addHandlingFilters(array $filters)
    {
        foreach ($filters as $filter) {
            $this->addHandlingFilter($filter);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function addHandlingFilter(FilterInterface $filter)
    {
        $this->handlingFilters[] = $filter;
    }
}
