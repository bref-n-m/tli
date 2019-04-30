<?php

namespace App\Controller;

use Beaver\Controller\AbstractController;
use Beaver\Response\JsonResponse;

class PathologyController extends AbstractController
{
    public function searchByKeywords()
    {
        return new JsonResponse([]);
    }
}
