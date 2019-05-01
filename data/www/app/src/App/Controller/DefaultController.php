<?php

namespace App\Controller;

use App\Auth\Hasher;
use Beaver\Controller\AbstractController;

class DefaultController extends AbstractController
{
    public function index()
    {
        return $this->render('index.html.twig', [
            'name' => 'Beaver',
        ]);
    }
}
