IcansLoggingComponent
=====================

A PHP Component providing:
- a Flume-Handler to write to a flume-node
- a FilterInterface to write filters which can be added to the handler and filter the data
- a PostProcessorInterface to write a postprocessor which can enrich the logged data
- a Timer to measure execution times
- a ProgressInformationInterface with default implementation to have progress messages in a CLI Command

Installation:
You can use composer to install the component from packagist:
icans/logging-component

Usages:

ThriftFlumeHandler:

<?php
$host =  localhost
$port =  9129
$thriftSocket = new Thrift\Transport\TSocket($host, $port);
$thriftTransport = new Thrift\Transport\TBufferedTransport($thriftSocket);
$thriftProtocol = new Thrift\Protocol\TBinaryProtocolAccelerated($thriftTransport);
$thriftFlumeClient = new ICANS\Component\IcansLoggingComponent\Flume\ThriftFlumeEventServerClient($thriftProtocol)


$thriftFlumeProcessingHandler = new ThriftFlumeProcessingHandler($thriftTransport, $thriftFlumeClient);

$formatter = new Monolog\Formatter\JsonFormatter();
$thriftFlumeProcessingHandler->setFormatter($formatter);

//the processor has to implement the ICANS\Component\IcansLoggingComponent\PostProcessorInterface
$processor = new myPostProcessor();
$thriftFlumeProcessingHandler->pushProcessor($processor);

$emptyFilter = new ICANS\Component\IcansLoggingComponent\Filter\EmptyFilter();
$thriftFlumeProcessingHandler->addFilter($emptyFilter);

$recordData = array('testdata' => 'test');

//will write to the flume node
$thriftFlumeProcessingHandler->write($recordData);


Contribute:

To
