<?php

namespace App\Controller;

use Beaver\Controller\AbstractController;
use Beaver\Response\JsonResponse;
use Beaver\Response\Response;

class DefaultController extends AbstractController
{
    public function index()
    {
        return new Response('<img src="https://banner2.kisspng.com/20180124/thw/kisspng-beaver-clip-art-hello-beaver-5a686ba614b066.0283876115167927420848.jpg">');
    }

    public function complex(string $slug, int $id)
    {
        return new JsonResponse([
            'id'   => $id,
            'slug' => $slug,
        ]);
    }
}
