<?php

namespace App\Controller;

use App\Auth\Authenticator;
use App\Service\Notificator;
use Beaver\Controller\AbstractController;
use Beaver\Request\Request;

class AuthenticatorController extends AbstractController
{
    public function login()
    {
        /** @var Authenticator $authenticator */
        $authenticator = $this->get('authenticator');

        if (Request::POST === $this->request->getHttpMethod()) {
            if (!$credentials = $this->getCredentials()) {
                $this->addNotification("Veuillez remplir tous les champs", 'danger');

                return $this->render('user/login.html.twig');
            }

            if (!$authenticator->connection($credentials['email'], $credentials['password'])) {
                $this->addNotification("Identifiants invalides", 'danger');

                return $this->render('user/login.html.twig');
            }

            $this->addNotification("Vous êtes connecté !", 'success');

            return $this->redirect($this->get('router')->generatePath('index'));
        }

        return $this->render('user/login.html.twig');
    }

    public function logout()
    {
        /** @var Authenticator $authenticator */
        $authenticator = $this->get('authenticator');
        $authenticator->disconnect();

        $this->addNotification('Vous avez bien été déconnecté !', 'success');

        $this->redirect($this->get('router')->generatePath('index'));
    }

    /**
     * Returns array with credentials
     *
     * @return array|null
     */
    public function getCredentials(): ?array
    {
        $email = $this->request->getPostValue('email');
        $password = $this->request->getPostValue('password');

        if (!$email || !$password) {
            return null;
        }

        return ['email' => $email, 'password' => $password];
    }
}
