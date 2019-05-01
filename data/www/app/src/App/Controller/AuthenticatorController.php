<?php

namespace App\Controller;

use App\Auth\Authenticator;
use App\Service\Notificator;
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
        /** @var Authenticator $authenticator */
        $authenticator = $this->get('authenticator');
        $authenticator->disconnect();

        /** @var Notificator $notificator */
        $notificator = $this->get('notificator');
        $notificator->addNotification('Vous avez bien été déconnecté!', 'success');

        $this->redirect($this->get('router')->generatePath('index'));
    }
}
