<?php

namespace App\Controller;

use App\Form\UserRegisterForm;
use Beaver\Controller\AbstractController;
use Beaver\Request\Request;
use Beaver\Response\Response;

class UserController extends AbstractController
{
    public function register()
    {
        /** @var UserRegisterForm $formValidator */
        $formValidator = $this->get('user.register.form');
        if (Request::POST === $this->request->getHttpMethod()) {
            if ($formData = $formValidator->validate($this->request)) {
                // form valid

                return $this->redirect($this->get('router')->generatePath('index'));
            }
            else {
                // form invalid
            }
        }

        return new Response('');
    }
}
