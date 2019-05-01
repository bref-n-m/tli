<?php

namespace App\Controller;

use App\Repository\SymptomRepository;
use Beaver\Controller\AbstractController;

class DefaultController extends AbstractController
{
    public function index()
    {
        return $this->render('index.html.twig', [
            'name' => 'Beaver',
        ]);
    }

    public function complex(string $slug, int $id)
    {
        /** @var Router $router */
        $router = $this->get('router');

        return new JsonResponse([
            'id'    => $id,
            'slug'  => $slug,
            'route' => $router->generatePath('complex', [
                'id'   => 123,
                'slug' => 'salut-ca-va',
            ])
        ]);
    }

    public function information()
    {
        return $this->render('information.html.twig', [
            'name' => 'Beaver',
        ]);
    }

    public function symptomes()
    {
        /** @var SymptomRepository $symptomRepository */
        $symptomRepository = $this->get('repository.symptom');

        return $this->render('symptomes.html.twig', [
            'symptoms' => $symptomRepository->getAll(),
        ]);
    }

    public function pathologie()
    {
        return $this->render('pathologie.html.twig', [
            'name' => 'Beaver',
        ]);
    }

    public function recherche()
    {
        return $this->render('search-page.html.twig', [
            'name' => 'Beaver',
        ]);
    }

    public function connection()
    {
        return $this->render('identification.html.twig', [
            'name' => 'Beaver',
        ]);
    }
}
