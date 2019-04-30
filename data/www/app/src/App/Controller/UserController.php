<?php

namespace App\Controller;

use App\Auth\UserManager;
use App\Form\UserRegisterForm;
use Beaver\Controller\AbstractController;
use Beaver\Request\Request;
use Beaver\Response\Response;

class UserController extends AbstractController
{
    public function register()
    {
        /** @var UserManager $userManager */
        $userManager = $this->get('user.manager');

        /** @var UserRegisterForm $formValidator */
        $formValidator = $this->get('user.register.form');

        if (Request::POST === $this->request->getHttpMethod()) {
            if ($formData = $formValidator->validate($this->request)) {
                // Error during insertion
                if (!$userManager->register($formData)) {
                    // TODO : Error during insertion (flag or anything else)
                }

                return $this->redirect($this->get('router')->generatePath('index'));
            } else {
                // form invalid
                // TODO : Error during insertion (flag or anything else)
            }
        }

        return new Response('Pas post');
    }
}
