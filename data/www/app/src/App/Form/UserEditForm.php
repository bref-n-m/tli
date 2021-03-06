<?php

namespace App\Form;

use Beaver\AbstractForm;

class UserEditForm extends AbstractForm
{
    /**
     * return the config of the form
     *
     * @return array
     */
    protected function build(): array
    {
        return [
            'first_name' => '.{1,50}',
            'last_name'  => '.{1,50}',
        ];
    }
}
