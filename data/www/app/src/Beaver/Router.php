<?php

namespace Beaver;

use Beaver\Request\Request;

class Router
{
    const URL_SEPARATOR = '/';
    const PARAMETER_DELIMITER = ':';
    const METHOD = 'method';
    const FALLBACK_ROUTE = 'fallbackRoute';

    /** @var array */
    private $routes;

    /**
     * If key fallbackRoute exists, it must be at the last element of $routes
     *
     * @param array $routes
     */
    public function setRoutes(array $routes): void
    {
        // weird code, but if the key self::FALLBACK_ROUTE exists in $routes, put it at the end of the array
        if (array_key_exists(self::FALLBACK_ROUTE, $routes['routes'])) {
            $fallbackRoute = $routes['routes'][self::FALLBACK_ROUTE];
            unset($routes['routes'][self::FALLBACK_ROUTE]);
            $routes['routes'][self::FALLBACK_ROUTE] = $fallbackRoute;
        }

        $this->routes = $routes;
    }

    public function generatePath(string $pathName, array $parameters = []): string
    {
        foreach ($this->routes['routes'] as $name => $route) {
            if ($name === $pathName) {
                $path = $route['path'];

                if(!isset($route['parameters'])) {
                    return $path;
                }

                // populate params in path
                foreach ($route['parameters'] as $parameterName => $parameterValue) {
                    $path = str_replace(
                        self::PARAMETER_DELIMITER.$parameterName,
                        $parameters[$parameterName],
                        $path
                    );
                }

                return $path;
            }
        }

        throw new \Exception("Could not generate route for $pathName");
    }

    /**
     * @param Request $request
     *
     * @return Route
     *
     * @throws \Exception
     */
    public function getRoute(Request $request): Route
    {
        foreach ($this->routes['routes'] as $route) {
            if (false !== $parameters = $this->match($request, $route)) {
                return new Route(
                    $route['controller'],
                    $parameters
                );
            }
        }

        throw new \Exception('Route not found for request '.$request->getHttpMethod().self::URL_SEPARATOR.$request->getPath());
    }

    /**
     * If the route matches, the array containing the parameters is returned, empty array otherwise
     *
     * @param Request $request
     *
     * @param array $route
     *
     * @return array|bool
     */
    private function match(Request $request, array $route)
    {
        // check method
        if (array_key_exists(self::METHOD, $route) && $route[self::METHOD] !== $request->getHttpMethod()) {
            return false;
        }

        // prepare regex
        $regex = str_replace(
            self::URL_SEPARATOR,
            '\\'.self::URL_SEPARATOR,
            $route['path']
        );

        // get parameters order & replace every parameter by its regex
        $parametersOrder = [];
        if (key_exists('parameters', $route)) {
            foreach ($route['parameters'] as $parameter => $value) {
                $order = strpos($regex, self::PARAMETER_DELIMITER.$parameter);
                $parametersOrder[$order] = $parameter;
            }
            ksort($parametersOrder);

            foreach ($route['parameters'] as $parameter => $value) {
                $regex = str_replace(
                    self::PARAMETER_DELIMITER.$parameter,
                    "($value)",
                    $regex
                );
            }
        }

        $match = preg_match(
            "/^$regex$/",
            self::URL_SEPARATOR.$request->getPath(),
            $parameters
        );
        return 0 === $match ? false : $this->buildParametersArray($parametersOrder, $parameters);
    }

    /**
     * @param array $parametersOrder
     * @param $orderedParameters
     *
     * @return array
     */
    private function buildParametersArray(array $parametersOrder, $orderedParameters): array
    {
        $parameters = [];
        $i = 1;
        foreach ($parametersOrder as $parameterName) {
            $parameters[$parameterName] = $orderedParameters[$i++];
        }
        return $parameters;
    }
}
