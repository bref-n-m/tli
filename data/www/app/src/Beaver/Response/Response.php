<?php

namespace Beaver\Response;

class Response
{
    /** @var string */
    private $html;

    /**
     * Response constructor.
     */
    public function __construct(string $html)
    {
        $this->html = $html;
    }

    public function send()
    {
        echo $this->html;
    }
}
