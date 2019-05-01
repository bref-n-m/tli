<?php

namespace App\Controller;

use Beaver\Controller\AbstractController;

class DefaultController extends AbstractController
{
    public function index()
    {
        return $this->render('index.html.twig', [
            'name' => 'Beaver',
        ]);
    }

    public function information()
    {
        return $this->render('information.html.twig', [
            'name' => 'Beaver',
        ]);
    }
}
