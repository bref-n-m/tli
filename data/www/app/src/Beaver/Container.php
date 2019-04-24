<?php

namespace Beaver;

use ReflectionClass;
use ReflectionParameter;

class Container
{
    const SERVICE_IDENTIFIER = '@';
    const PARAMETER_IDENTIFIER = '$';

    /** @var array */
    private $instances = [];

    /** @var array */
    private $config;

    /**
     * @param array $config
     */
    public function setConfig(array $config): void
    {
        $this->config = $config;
    }

    /**
     * Resolve a given service
     *
     * @param string $serviceName
     *
     * @return object
     *
     * @throws \ReflectionException|\Exception
     */
    public function resolve(string $serviceName)
    {
        return $this->findInstance($serviceName) ?: $this->createInstance($serviceName);
    }

    /**
     * Return the instance of a given service if it exists
     *
     * @param string $serviceName
     *
     * @return mixed|null
     */
    private function findInstance(string $serviceName)
    {
        return key_exists($serviceName, $this->instances) ? $this->instances[$serviceName] : null;
    }

    /**
     * Create and save an instance of a given service
     *
     * @param string $serviceName
     *
     * @return object
     *
     * @throws \ReflectionException|\Exception
     */
    private function createInstance(string $serviceName)
    {
        $serviceConfig = $this->config['services'][$serviceName];
        if (null === $serviceConfig) {
            throw new \Exception("Could not resolve service $serviceName");
        }

        $reflector = new ReflectionClass($serviceConfig['class']);
        if (!$reflector->isInstantiable()) {
            throw new \Exception("Service $serviceName is not instantiable");
        }

        $constructor = $reflector->getConstructor();
        if (null === $constructor) {
            $instance = $reflector->newInstance();
            // save the instance
            $this->instances[$serviceName] = $instance;
            return $instance;
        }

        $parameters = $this->resolveParameters(
            $constructor->getParameters(),
            $serviceConfig['parameters']
        );
        $instance = $reflector->newInstanceArgs($parameters);

        // save the instance
        $this->instances[$serviceName] = $instance;

        return $instance;
    }

    /**
     * Resolve the parameters according to the di configuration and the constructor parameters
     *
     * @param array $constructorParameters
     * @param array $serviceConfigParameters
     *
     * @return array
     *
     * @throws \ReflectionException
     */
    private function resolveParameters(array $constructorParameters, array $serviceConfigParameters): array
    {
        $parameters = [];
        /** @var ReflectionParameter $constructorParameter */
        foreach ($constructorParameters as $constructorParameter) {
            $parameters[] = $this->resolveParameter(
                $serviceConfigParameters[self::PARAMETER_IDENTIFIER.$constructorParameter->getName()]
            );
        }
        return $parameters;
    }

    /**
     * Resolve one parameter (service or value)
     *
     * @param $parameterValue
     *
     * @return object
     *
     * @throws \ReflectionException
     */
    private function resolveParameter(string $parameterValue)
    {
        return self::SERVICE_IDENTIFIER === substr($parameterValue, 0, 1)
            ? $this->resolve(ltrim($parameterValue, self::SERVICE_IDENTIFIER)) : $parameterValue;
    }
}
