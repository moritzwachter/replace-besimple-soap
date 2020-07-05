<?php

namespace AppBundle\Tests\Controller\SOAP;

/**
 * @group functional
 */
class LivestreamApiSoapClient extends \SoapClient
{

    /**
     * @param array $options A array of config values
     * @param string $wsdl The wsdl file to use
     * @throws \SoapFault
     */
    public function __construct(array $options, string $wsdl)
    {
        $options = array_merge(array(
            'features' => 1,
        ), $options);

        parent::__construct($wsdl, $options);
    }

    public function setRecordingInformation(string $identifier, string $startDate, string $endDate): int
    {
        return $this->__soapCall('setRecordingInformation', array($identifier, $startDate, $endDate));
    }

    public function startLiveStream(string $identifier, string $startTime): int
    {
        return $this->__soapCall('startLiveStream', array($identifier, $startTime));
    }

    public function stopLiveStream(string $identifier, string $stopTime): int
    {
        return $this->__soapCall('stopLiveStream', array($identifier, $stopTime));
    }
}
