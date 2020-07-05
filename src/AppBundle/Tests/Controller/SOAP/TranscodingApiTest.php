<?php

namespace AppBundle\Tests\Controller\SOAP;


use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TranscodingApiTest extends KernelTestCase
{
    public function testSuccessCall()
    {
        $opts = array(
            'http' => array(
                'user_agent' => 'PHPSoapClient'
            )
        );
        $context = stream_context_create($opts);

        $soapClientOptions = array(
            'stream_context' => $context,
            'cache_wsdl' => WSDL_CACHE_NONE,
            'trace' => true
        );

        $wsdl = 'http://host.docker.internal/wsdl/TranscodingApi?wsdl';

        $client = new TranscodingApiSoapClient($soapClientOptions, $wsdl);
        // success call
        $response = $client->success('0123456__a12345_test.mxf');
        $this->assertSame(1, $response);

        // error call
        $response = $client->error('0123456__a12345_test.mxf');
        $this->assertSame(1, $response);
    }
}
