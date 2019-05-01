<?php

namespace App\Controller;

use App\Repository\PathologyRepository;
use Beaver\Controller\AbstractController;
use Beaver\Response\JsonResponse;
use Beaver\Router;

class PathologyController extends AbstractController
{
    public function index()
    {
        return $this->render('pathology/index.html.twig', [
            'name' => 'Beaver',
        ]);
    }

    public function search()
    {
        return $this->render('pathology/search.html.twig', [
            'name' => 'Beaver',
        ]);
    }

    public function searchByKeywords()
    {
        /** @var Router $router */
        $router = $this->get('router');

        return $this->render('pathology/search.html.twig', [
            'apiSearchUri' => $router->generatePath('api.searchByKeywords')
        ]);
    }

    public function apiSearchByKeywords()
    {
        /** @var PathologyRepository $pathologyRepository */
        $pathologyRepository = $this->get('repository.pathology');

        $keywords = $this->request->getPostValue('keywords');
        $keywords = $keywords ? json_decode($keywords) : [];

        return new JsonResponse([
            'pathologies' => $pathologyRepository->findByKeyWords($keywords)
        ]);
    }
}
