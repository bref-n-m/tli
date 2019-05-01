<?php

namespace App\Controller;

use App\Auth\Authenticator;
use App\Repository\PathologyRepository;
use Beaver\Controller\AbstractController;
use Beaver\Response\JsonResponse;
use Beaver\Response\Response;

class PathologyController extends AbstractController
{
    public function index()
    {
        return $this->render('pathology/index.html.twig', [
            'name' => 'Beaver',
        ]);
    }

    public function searchByKeywords()
    {
        /** @var Authenticator $authenticator */
        $authenticator = $this->get('authenticator');
        if (!$authenticator->getUser()) {
            $this->addNotification('Vous n\'etes pas connecté, la page est inaccessible.', 'danger');
            return $this->redirect($this->get('router')->generatePath('index'));
        }

        return $this->render('pathology/searchByKeywords.html.twig');
    }

    public function apiSearchByKeywords()
    {
        /** @var Authenticator $authenticator */
        $authenticator = $this->get('authenticator');
        if (!$authenticator->getUser()) {
            return new JsonResponse([
                'message' => 'Vous n\'etes pas connecté, la page est inaccessible',
            ], Response::HTTP_FORBIDDEN);
        }

        /** @var PathologyRepository $pathologyRepository */
        $pathologyRepository = $this->get('repository.pathology');

        $keywords = $this->request->getPostValue('keywords');
        $keywords = $keywords ? json_decode($keywords) : [];

        return new JsonResponse([
            'pathologies' => $pathologyRepository->findByKeyWords($keywords)
        ]);
    }
}
