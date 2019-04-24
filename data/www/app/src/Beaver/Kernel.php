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

    /** @var string  */
    private $path;

    /** @var Container  */
    private $container;

    /** @var Router */
    private $router;

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
            $this->container
        ]);

        $action = $route->getAction();

        // prepare parameters
        $methodParameters = $reflector->getMethod($action)->getParameters();
        $orderedParameters = [];
        foreach ($methodParameters as $parameter) {
            $orderedParameters[] = $route->getParameters()[$parameter->getName()];
        }

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

                $config = array_merge(
                    $config,
                    yaml_parse_file($configDirectory.$file)
                );
            }
        }
        return $config;
    }
}
