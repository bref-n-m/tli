<?php

namespace App\Form;

use Beaver\AbstractForm;

class UserEditPasswordForm extends AbstractForm
{

    /**
     * return the config of the form
     *
     * @return array
     */
    protected function build(): array
    {
        return [
            'password'         => '.{6,100}',
            'password_confirm' => '.{6,100}',
        ];
    }
}
