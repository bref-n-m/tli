<?php

namespace App\Controller;

use App\Repository\SymptomRepository;
use Beaver\Controller\AbstractController;

class SymptomController extends AbstractController
{
    public function index($page = 1)
    {
        /** @var SymptomRepository $symptomRepository */
        $symptomRepository = $this->get('repository.symptom');

        return $this->render('symptom/index.html.twig', [
            'symptoms' => $symptomRepository->getAllPaginated($page, 'desc'),
        ]);

    }
}
