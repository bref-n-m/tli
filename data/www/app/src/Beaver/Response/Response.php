<?php

namespace Beaver\Response;

class Response
{
    const HTTP_OK = 200;
    const HTTP_FORBIDDEN = 403;
    const HTTP_NOT_FOUND = 404;

    /** @var string */
    private $html;

    /** @var int */
    private $status;

    /**
     * Response constructor.
     *
     * @param string $html
     * @param int $status
     */
    public function __construct(string $html, int $status = self::HTTP_OK)
    {
        $this->html = $html;
        $this->status = $status;
    }

    public function send()
    {
        http_response_code($this->status);
        echo $this->html;
    }
}
