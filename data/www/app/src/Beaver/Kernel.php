<?php

namespace Beaver;

use Beaver\Controller\AbstractController;
use Beaver\Request\Request;
use Beaver\Response\Response;
use ReflectionClass;

class Kernel
{
    const CONFIG_DIRECTORY = 'config'.DIRECTORY_SEPARATOR;
    const DEPENDENCY_INJECTION_DIRECTORY = self::CONFIG_DIRECTORY.'DependencyInjection'.DIRECTORY_SEPARATOR;
    const ROUTING_DIRECTORY = self::CONFIG_DIRECTORY.'Routing'.DIRECTORY_SEPARATOR;
    const TEMPLATES_DIRECTORY = 'src'.DIRECTORY_SEPARATOR.'App'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR;

    /** @var string  */
    private $path;

    /** @var Container  */
    private $container;

    /** @var Router */
    private $router;

    /** @var \Twig\Environment  */
    private $twig;

    /**
     * Kernel constructor.
     *
     * @param string $path
     *
     * @throws \Exception
     */
    public function __construct(string $path)
    {
        $this->path = $path;

        // create a container for dependency injection
        $this->container = new Container();

        // load di config
        $this->loadDependencyInjectionConfig();

        // router
        $this->router = $this->container->resolve('router');
        $this->loadRoutingConfig();

        // load twig config
        $this->twig = $this->loadTwigConfig();
    }

    /**
     * Handle a request, search for the correct route and return an appropriate response
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \ReflectionException
     */
    public function handle(Request $request)
    {
        /** @var Route $route */
        $route = $this->router->getRoute($request);

        $reflector = new ReflectionClass($route->getController());
        /** @var AbstractController $controllerInstance */
        $controllerInstance = $reflector->newInstanceArgs([
            $this->container,
            $this->twig,
            $request
        ]);

        $action = $route->getAction();

        // prepare parameters
        $methodParameters = $reflector->getMethod($action)->getParameters();
        $orderedParameters = [];
        $routeParameters = $route->getParameters();
        foreach ($methodParameters as $parameter) {
            if (array_key_exists($parameter->getName(), $routeParameters)) {
                $orderedParameters[] = $routeParameters[$parameter->getName()];
            }
        }

        // add special service to the container
        $this->container->addSpecialService('request', $request);

        return $reflector->getMethod($action)->invokeArgs(
            $controllerInstance,
            $orderedParameters
        );
    }

    private function loadDependencyInjectionConfig()
    {
        $diConfigDirectories = [
            $this->path.self::DEPENDENCY_INJECTION_DIRECTORY,
            $this->path.'src'.DIRECTORY_SEPARATOR.'Beaver'.DIRECTORY_SEPARATOR.self::DEPENDENCY_INJECTION_DIRECTORY,
        ];

        $this->container->setConfig(
            $this->loadYamlFiles($diConfigDirectories)
        );
    }

    private function loadRoutingConfig()
    {
        $diConfigDirectories = [
            $this->path.self::ROUTING_DIRECTORY,
            $this->path.'src'.DIRECTORY_SEPARATOR.'Beaver'.DIRECTORY_SEPARATOR.self::ROUTING_DIRECTORY,
        ];

        $this->router->setRoutes(
            $this->loadYamlFiles($diConfigDirectories)
        );
    }

    private function loadYamlFiles(array $configDirectories)
    {
        $config = [];
        foreach ($configDirectories as $configDirectory) {
            $configFiles = scandir($configDirectory);
            foreach ($configFiles as $file) {
                if ('.' === $file || '..' === $file) {
                    continue;
                }

                $config = array_merge_recursive(
                    $config,
                    yaml_parse_file($configDirectory.$file)
                );
            }
        }

        return $config;
    }

    private function loadTwigConfig()
    {
        $twigEnvironment = new TwigLoader(
            [$this->path.self::TEMPLATES_DIRECTORY],
            $this->router
        );
        return $twigEnvironment->getTwig();
    }
}
