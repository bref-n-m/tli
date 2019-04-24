<?php

namespace Beaver\Request;

class Request
{
    /** @var string */
    private $httpMethod;

    /** @var string */
    private $path;

    /** @var array */
    private $post;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->httpMethod = $_SERVER['REQUEST_METHOD'];
        $this->path = key_exists('path', $_GET) ? $_GET['path'] : '';
        $this->post = $_POST;
    }

    /**
     * @return string
     */
    public function getHttpMethod(): string
    {
        return $this->httpMethod;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $key
     *
     * @return array
     */
    public function getPost(string $key): array
    {
        return $this->post[$key];
    }
}
