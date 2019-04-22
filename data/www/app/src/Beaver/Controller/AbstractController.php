<?php

namespace Beaver\Controller;

use Beaver\Container;

abstract class AbstractController
{
    /** @var Container */
    private $container;

    /**
     * AbstractController constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $service
     *
     * @return object
     *
     * @throws \ReflectionException
     */
    protected function get(string $service)
    {
        return $this->container->resolve($service);
    }
}
