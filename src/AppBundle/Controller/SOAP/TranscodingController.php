<?php

namespace AppBundle\Controller\SOAP;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TranscodingController extends Controller
{
    /**
     * @Soap\Method("success")
     * @Soap\Param("transcodedFile", phpType = "string")
     * @Soap\Result(phpType = "int")
     */
    public function successAction($transcodedFile)
    {
        $transcoder = $this->getFakeService('video.transcoder');

        $values = array('transcodedFile' => $transcodedFile);
        $this->get('logger')->info(sprintf('[TranscodingController] Method "%s" is called', __METHOD__), $values);

        $transcoder->handle($transcodedFile);

        return $this->get('besimple.soap.response')->setReturnValue(1);
    }

    /**
     * @Soap\Method("error")
     * @Soap\Param("transcodedFile", phpType = "string")
     * @Soap\Result(phpType = "int")
     */
    public function errorAction($transcodedFile)
    {
        $values = array('transcodedFile' => $transcodedFile);
        $this->get('logger')->info(sprintf('[TranscodingController] Method "%s" is called', __METHOD__), $values);

        $notification = $this->getFakeService('notification.factory')->create('Transcoding', $values, new \DateTime());
        $this->getFakeService('doctrine')->persistAndFlush($notification);

        return $this->get('besimple.soap.response')->setReturnValue(1);
    }


    public function getFakeService(string $serviceId)
    {
        return $this->container->get('fake.container')->get($serviceId);
    }
}
