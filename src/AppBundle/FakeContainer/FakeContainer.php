<?php

namespace AppBundle\FakeContainer;

use Symfony\Component\DependencyInjection\ContainerInterface;

class FakeContainer
{
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function get(string $serviceId)
    {
        return new FakeService($serviceId);
    }
}
