<?php

namespace AppBundle\FakeContainer;

class FakeService
{
    /** @var string */
    public $serviceId;

    public function __construct(?string $serviceId = '')
    {
        $this->serviceId = $serviceId;
    }

    public function __call($name, $arguments)
    {
        return;
    }
}
