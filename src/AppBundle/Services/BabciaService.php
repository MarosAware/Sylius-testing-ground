<?php

namespace AppBundle\Services;

use Sylius\Component\Resource\Factory\FactoryInterface;

class BabciaService
{
    private $factory;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function showFactory()
    {
        $factoryInterface = $this->factory->createNew();
        return $factoryInterface;
    }

}