<?php

namespace Beaver\Response;

class JsonResponse extends Response
{
    /**
     * JsonResponse constructor.
     *
     * @param array $array
     * @param int $status
     */
    public function __construct(array $array, int $status = self::HTTP_OK)
    {
        parent::__construct(
            json_encode($array, JSON_UNESCAPED_SLASHES),
            $status
        );
    }

    public function send()
    {
        header('Content-Type: application/json');
        parent::send();
    }
}
