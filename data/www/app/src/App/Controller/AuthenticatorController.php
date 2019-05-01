<?php

namespace App\Controller;

use Beaver\Controller\AbstractController;

class AuthenticatorController extends AbstractController
{
    public function login()
    {
        // TODO
        return $this->render('user/login.html.twig', [
            'name' => 'Beaver',
        ]);
    }

    public function logout()
    {
        // TODO
    }
}
