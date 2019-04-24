<?php

namespace Beaver;

class Route
{
    const ACTION_SEPARATOR = '::';

    /** @var string */
    private $controller;

    /** @var string */
    private $action;

    /** @var array */
    private $parameters;

    /**
     * Route constructor.
     *
     * @param string $action
     * @param array $parameters
     */
    public function __construct(string $action, array $parameters)
    {
        $action = explode(self::ACTION_SEPARATOR, $action);
        $this->controller = $action[0];
        $this->action = $action[1];
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
