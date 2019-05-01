<?php

namespace Beaver\Controller;

use App\Auth\Authenticator;
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
     * @throws \ReflectionException
     */
    protected function render(string $template, array $params = [], int $status = Response::HTTP_OK)
    {
        /** @var Authenticator $authenticator */
        $authenticator = $this->get('authenticator');

        $params = array_merge($params, [
            'user' => $authenticator->getUser(),
        ]);

        return new Response(
            $this->twig->render($template, $params),
            $status
        );
    }

    /**
     * Redirect to a specific url
     *
     * @param string $url
     */
    protected function redirect(string $url): void
    {
        header('Status: 301 Moved Permanently', false, 301);
        header("Location: $url");
    }
}
