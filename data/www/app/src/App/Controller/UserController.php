<?php

namespace App\Controller;

use App\Auth\UserManager;
use App\Form\UserDeleteForm;
use App\Form\UserEditForm;
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
                    $this->addNotification('Un problème est survenu durant l\'enregistrement ! Veuillez vérifier que les mots de passe sont identiques !', 'danger');

                    return $this->render('user/register.html.twig');
                }
                $this->addNotification('Votre compte à bien été créé, pensez à vous connecter !', 'success');

                return $this->redirect($this->get('router')->generatePath('index'));
            } else {
                $this->addNotification('Certains champs n\'ont pas le format attendu', 'danger');

                return $this->render('user/register.html.twig');
            }
        }

        return $this->render('user/register.html.twig');
    }

    public function edit()
    {
        /** @var UserManager $userManager */
        $userManager = $this->get('user.manager');

        /** @var UserEditForm $formValidator */
        $formValidator = $this->get('user.edit.form');

        if (Request::POST === $this->request->getHttpMethod()) {
            if ($formData = $formValidator->validate($this->request)) {
                // Error during updating
                if (!$userManager->update($formData)) {
                    // TODO : Error during update (flag or anything else)
                }

                return $this->redirect($this->get('router')->generatePath('index'));
            } else {
                // form invalid
                // TODO : Error during update (flag or anything else)
            }
        }

        return new Response('Pas post');
    }

    public function delete()
    {
        /** @var UserManager $userManager */
        $userManager = $this->get('user.manager');

        /** @var UserDeleteForm $formValidator */
        $formValidator = $this->get('user.delete.form');

        if (Request::POST === $this->request->getHttpMethod()) {
            if ($formData = $formValidator->validate($this->request)) {
                // Error during deleting
                if (!$userManager->delete($formData)) {
                    // TODO : Error during delete (flag or anything else)
                }

                return $this->redirect($this->get('router')->generatePath('index'));
            } else {
                // form invalid
                // TODO : Error during delete (flag or anything else)
            }
        }

        return new Response('Pas post');
    }
}
