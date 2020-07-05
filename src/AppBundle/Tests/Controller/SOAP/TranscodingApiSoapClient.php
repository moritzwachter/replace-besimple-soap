<?php

namespace AppBundle\Tests\Controller\SOAP;

class TranscodingApiSoapClient extends \SoapClient
{
    /**
     * @param array $options A array of config values
     * @param string $wsdl The wsdl file to use
     * @throws \SoapFault
     */
    public function __construct(array $options, string $wsdl)
    {
        $options = array_merge($options, array(
            'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
        ));

        parent::__construct($wsdl, $options);
    }

    /**
     * @param string $transcodedFile
     * @return int
     */
    public function success(string $transcodedFile): int
    {
        return $this->__soapCall('success', array($transcodedFile));
    }

    /**
     * @param string $transcodedFile
     * @return int
     */
    public function error(string $transcodedFile): int
    {
        return $this->__soapCall('error', array($transcodedFile));
    }
}

