<?php

namespace AppBundle\Tests\Controller\SOAP;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class LivestreamApiTest extends KernelTestCase
{
    /** @var string */
    private $wsdl;

    /** @var array  */
    private $soapClientOptions;

    public function setUp(): void
    {
        $opts = array(
            'http' => array(
                'user_agent' => 'PHPSoapClient'
            )
        );
        $context = stream_context_create($opts);

        $this->soapClientOptions = array(
            'stream_context' => $context,
            'cache_wsdl' => WSDL_CACHE_NONE,
            'trace' => true
        );

        $this->wsdl = 'http://host.docker.internal/wsdl/LivestreamApi?wsdl';
    }

    public function testSetRecordingInformation()
    {
        $start = new \DateTime('now');
        $end = new \DateTime('tomorrow');

        $client = new LivestreamApiSoapClient($this->soapClientOptions, $this->wsdl);
        $response = $client->setRecordingInformation(
            1234,
            $start->format(\DateTime::ATOM),
            $end->format(\DateTime::ATOM)
        );
        $this->assertEquals(1, $response);
    }

    public function testStartLiveStream()
    {
        $start = new \DateTime('now');

        $client = new LivestreamApiSoapClient($this->soapClientOptions, $this->wsdl);
        $response = $client->startLiveStream(
            1234,
            $start->format(\DateTime::ATOM)
        );
        $this->assertEquals(1, $response);
    }

    public function testStopLiveStream()
    {
        $stop = new \DateTime('now');

        $client = new LivestreamApiSoapClient($this->soapClientOptions, $this->wsdl);
        $response = $client->stopLiveStream(
            1234,
            $stop->format(\DateTime::ATOM)
        );
        $this->assertEquals(1, $response);
    }
}
