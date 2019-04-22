<?php

namespace Beaver;

use Beaver\Request\Request;

class Router
{
    const URL_SEPARATOR = '/';

    /** @var array */
    private $routes;

    /**
     * @param array $routes
     */
    public function setRoutes(array $routes): void
    {
        $this->routes = $routes;
    }

    /**
     * @param Request $request
     *
     * @return string
     *
     * @throws \Exception
     */
    public function getRoute(Request $request): string
    {
        foreach ($this->routes['routes'] as $route) {
            if ($this->match($request, $route)) {
                return $route['controller'];
            }
        }

        throw new \Exception('Route not found for request '.$request->getHttpMethod().self::URL_SEPARATOR.$request->getPath());
    }

    /**
     * @param Request $request
     *
     * @param array $route
     *
     * @return bool
     */
    private function match(Request $request, array $route): bool
    {
        if (ltrim($route['path'], self::URL_SEPARATOR) === $request->getPath()) {
            return true;
        }
        return false;
    }
}
