<?php

namespace AppBundle\Controller\SOAP;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LivestreamController extends Controller
{

    /**
     * @Soap\Method("setRecordingInformation")
     * @Soap\Param("identifier", phpType = "string")
     * @Soap\Param("startDate", phpType = "dateTime")
     * @Soap\Param("endDate", phpType = "dateTime")
     * @Soap\Result(phpType = "int")

     * @param $identifier
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     *
     * @return
     */
    public function setRecordingInformationAction($identifier, \DateTime $startDate, \DateTime $endDate)
    {
        /* @var $logger \Psr\Log\LoggerInterface */
        $logger = $this->get('logger');

        $values = array(
            'identifier' => $identifier,
            'startDate' => $startDate->format('Y-m-d H:i:s'),
            'endDate' => $endDate->format('Y-m-d H:i:s'),
        );

        $logger->info(sprintf('Method "%s" is called', __METHOD__), $values);

        $notification = $this->getFakeService('notification.factory')->create('Livestream', $values);
        $livestream = $this->getFakeService('livestream.repository')->getByLivestreamId($identifier);

        if (!empty($livestream)) {
            $logger->info(
                sprintf(
                    'Handle Livestream with ID "%s" (%s)',
                    $livestream->getId(),
                    $livestream->getTitle()
                )
            );

            if ($livestream->isAutomaticRecordingEnabled()) {
                $this->getFakeService('livestream.recorder')->doAutomaticRecordering($livestream);

                // ...
            }
        }

        $this->getFakeService('doctrine')->persistAndFlush($notification);

        return $this->get('besimple.soap.response')->setReturnValue(1);
    }

    /**
     * @Soap\Method("startLiveStream")
     * @Soap\Param("identifier", phpType = "string")
     * @Soap\Param("startTime", phpType = "dateTime")
     * @Soap\Result(phpType = "int")
     */
    public function startLiveStreamAction($identifier, \DateTime $startTime)
    {
        $values = array('identifier' => $identifier, 'startTime' => $startTime->format('Y-m-d H:i:s'));
        $this->get('logger')->info(sprintf('Method "%s" is called', __METHOD__), $values);

        $notification = $this->getFakeService('notification.factory')->create('Livestream', $values, new \DateTime());
        $this->getFakeService('doctrine')->persistAndFlush($notification);

        return $this->get('besimple.soap.response')->setReturnValue(1);
    }

    /**
     * @Soap\Method("stopLiveStream")
     * @Soap\Param("identifier", phpType = "string")
     * @Soap\Param("stopTime", phpType = "dateTime")
     * @Soap\Result(phpType = "int")
     */
    public function stopLiveStreamAction($identifier, \DateTime $stopTime)
    {
        $values = array('identifier' => $identifier, 'stopTime' => $stopTime->format('Y-m-d H:i:s'));
        $this->get('logger')->info(sprintf('Method "%s" is called', __METHOD__), $values);

        $notification = $this->getFakeService('notification.factory')->create('Livestream', $values, new \DateTime());
        $this->getFakeService('doctrine')->persistAndFlush($notification);

        return $this->get('besimple.soap.response')->setReturnValue(1);
    }

    public function getFakeService(string $serviceId)
    {
        return $this->container->get('fake.container')->get($serviceId);
    }
}
