<?php

namespace Beaver\Controller;

use Beaver\Container;
use Beaver\Response\Response;
use Twig\Environment;

abstract class AbstractController
{
    /** @var Container */
    private $container;

    /** @var Environment */
    private $twig;

    /**
     * AbstractController constructor.
     *
     * @param Container $container
     * @param Environment $twig
     */
    public function __construct(Container $container, Environment $twig)
    {
        $this->container = $container;
        $this->twig = $twig;
    }

    protected function get(string $service)
    {
        return $this->container->resolve($service);
    }

    protected function render(string $template, array $params = [], int $status = Response::HTTP_OK)
    {
        return new Response(
            $this->twig->render($template, $params),
            $status
        );
    }
}
