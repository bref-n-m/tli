<?php

namespace App\Controller;

use Beaver\Controller\AbstractController;
use Beaver\Response\Response;

class ErrorController extends AbstractController
{
    public function notFound($uri)
    {
        return $this->render('error/404.html.twig', [
            'uri' => $uri
        ], Response::HTTP_NOT_FOUND);
    }
}
