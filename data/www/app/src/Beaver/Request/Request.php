<?php

namespace Beaver\Request;

class Request
{
    const POST = 'POST';

    /**
     * @return string
     */
    public function getHttpMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return key_exists('path', $_GET) ? $_GET['path'] : '';
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function getPostValue(string $key)
    {
        return array_key_exists($key, $_POST) ? $_POST[$key] : null;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function getSessionValue(string $key)
    {
        return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : null;
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return string
     */
    public function setSessionValue(string $key, string $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param string $key
     */
    public function unsetSessionValue(string $key)
    {
        if (array_key_exists($key, $_SESSION)) {
            unset($_SESSION[$key]);
        }
    }
}
