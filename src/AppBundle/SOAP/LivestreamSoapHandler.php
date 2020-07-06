<?php

namespace AppBundle\SOAP;

use AppBundle\FakeContainer\FakeService;
use Psr\Log\LoggerInterface;

class LivestreamSoapHandler
{
    /** @var FakeService */
    private $livestreamRecorder;

    /** @var LoggerInterface */
    private $logger;

    /** @var FakeService */
    private $notificationFactory;

    /** @var FakeService */
    private $livestreamRepository;

    /** @var FakeService */
    private $doctrine;

    /**
     * @param FakeService $livestreamRecorder
     * @param LoggerInterface $logger
     * @param FakeService $notificationFactory
     * @param FakeService $livestreamRepository
     * @param FakeService $doctrine
     */
    public function __construct(
        FakeService $livestreamRecorder,
        LoggerInterface $logger,
        FakeService $notificationFactory,
        FakeService $livestreamRepository,
        FakeService $doctrine
    ) {
        $this->livestreamRecorder = $livestreamRecorder;
        $this->logger = $logger;
        $this->notificationFactory = $notificationFactory;
        $this->livestreamRepository = $livestreamRepository;
        $this->doctrine = $doctrine;
    }


    public function setRecordingInformation($identifier, string $startDate, string $endDate): int
    {
        $startDate = new \DateTime($startDate);
        $endDate = new \DateTime($endDate);

        $values = array(
            'identifier' => $identifier,
            'startDate' => $startDate->format('Y-m-d H:i:s'),
            'endDate' => $endDate->format('Y-m-d H:i:s'),
        );

        $this->logger->info(sprintf('Method "%s" is called', __METHOD__), $values);

        $notification = $this->notificationFactory->create('Livestream', $values);
        $livestream = $this->livestreamRepository->getByLivestreamId($identifier);

        if (!empty($livestream)) {
            $this->logger->info(
                sprintf(
                    'Handle Livestream with ID "%s" (%s)',
                    $livestream->getId(),
                    $livestream->getTitle()
                )
            );

            if ($livestream->isAutomaticRecordingEnabled()) {
                $this->livestreamRecorder->doAutomaticRecordering($livestream);

                // ...
            }
        }

        $this->doctrine->persistAndFlush($notification);

        return 1;
    }

    public function startLiveStream($identifier, string $startTime): int
    {
        $startTime = new \DateTime($startTime);

        $values = array('identifier' => $identifier, 'startTime' => $startTime->format('Y-m-d H:i:s'));
        $this->logger->info(sprintf('Method "%s" is called', __METHOD__), $values);

        $notification = $this->notificationFactory->create('Livestream', $values, new \DateTime());
        $this->doctrine->persistAndFlush($notification);

        return 1;
    }

    public function stopLiveStream($identifier, string $stopTime): int
    {
        $stopTime = new \DateTime($stopTime);

        $values = array('identifier' => $identifier, 'stopTime' => $stopTime->format('Y-m-d H:i:s'));
        $this->logger->info(sprintf('Method "%s" is called', __METHOD__), $values);

        $notification = $this->notificationFactory->create('Livestream', $values, new \DateTime());
        $this->doctrine->persistAndFlush($notification);

        return 1;
    }
}
