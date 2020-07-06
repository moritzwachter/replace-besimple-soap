<?php

namespace AppBundle\SOAP;

use AppBundle\FakeContainer\FakeService;
use Psr\Log\LoggerInterface;

class TranscodingSoapHandler
{
    /** @var FakeService */
    protected $transcoder;

    /** @var LoggerInterface */
    protected $logger;

    /** @var FakeService */
    protected $notificationFactory;

    /** @var FakeService */
    protected $doctrine;

    /**
     * @param FakeService $transcoder
     * @param LoggerInterface $logger
     * @param FakeService $notificationFactory
     * @param FakeService $doctrine
     */
    public function __construct(
        FakeService $transcoder,
        LoggerInterface $logger,
        FakeService $notificationFactory,
        FakeService $doctrine
    ) {
        $this->transcoder = $transcoder;
        $this->logger = $logger;
        $this->notificationFactory = $notificationFactory;
        $this->doctrine = $doctrine;
    }

    public function success(string $transcodedFile): int
    {
        $values = array('transcodedFile' => $transcodedFile);
        $this->logger->info(sprintf('[TranscodingController] Method "%s" is called', __METHOD__), $values);

        $this->transcoder->handle($transcodedFile);

        return 1;
    }

    public function error(string $transcodedFile): int
    {
        $values = array('transcodedFile' => $transcodedFile);
        $this->logger->info(sprintf('[TranscodingController] Method "%s" is called', __METHOD__), $values);

        $notification = $this->notificationFactory->create('Transcoding', $values, new \DateTime());
        $this->doctrine->persistAndFlush($notification);

        return 1;
    }
}
