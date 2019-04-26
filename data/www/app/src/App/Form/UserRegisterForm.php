<?php

namespace App\Form;

use Beaver\AbstractForm;

class UserRegisterForm extends AbstractForm
{
    /**
     * return the config of the form
     *
     * @return array
     */
    protected function build(): array
    {
        return [
            'email'            => '[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}',
            'first_name'       => '.{1,100}',
            'last_name'        => '.{1,100}',
            'password'         => '.{6,100}',
            'password_confirm' => '.{6,100}',
        ];
    }
}
