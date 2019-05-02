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
        /** @var MeridianRepository $meridianRepository */

        $meridianRepository = $this->get('repository.meridian');
        return $this->render('pathology/index.html.twig', [
            'meridians' => $meridianRepository->getAll(),
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

    public function apiFilterByMeridian()
    {
        /** @var PathologyRepository $pathologyRepository */
        $pathologyRepository = $this->get('repository.pathology');

        $filters = $this->request->getPostValue('filters');
        $filters = $filters ? json_decode($filters) : [];

        $pathologies = $filters ? $pathologyRepository->findByFilters($filters) : $pathologyRepository->findAll();

        return new JsonResponse([
            'pathologies' => $pathologies,
        ]);
    }
}
