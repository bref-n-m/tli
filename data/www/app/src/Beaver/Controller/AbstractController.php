<?php

namespace Beaver\Controller;

use Beaver\Container;
use Beaver\Request\Request;
use Beaver\Response\Response;
use Twig\Environment;

abstract class AbstractController
{
    /** @var Container */
    private $container;

    /** @var Environment */
    private $twig;

    /** @var Request */
    protected $request;

    /**
     * AbstractController constructor.
     *
     * @param Container $container
     * @param Environment $twig
     */
    public function __construct(Container $container, Environment $twig, Request $request)
    {
        $this->container = $container;
        $this->twig = $twig;
        $this->request = $request;
    }

    /**
     * Resolve a service from the container
     *
     * @param string $service
     * @return object
     *
     * @throws \ReflectionException
     */
    protected function get(string $service)
    {
        return $this->container->resolve($service);
    }

    /**
     * Return a render of a twig template with its params and a status code
     *
     * @param string $template
     * @param array $params
     * @param int $status
     *
     * @return Response
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    protected function render(string $template, array $params = [], int $status = Response::HTTP_OK)
    {
        return new Response(
            $this->twig->render($template, $params),
            $status
        );
    }

    /**
     * Redirect to a specific url
     *
     * @param $path
     */
    protected function redirect($url)
    {
        header("Location: $url");
    }
}
