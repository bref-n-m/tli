<?php

namespace App\Form;

use Beaver\AbstractForm;

class UserDeleteForm extends AbstractForm
{
    /**
     * return the config of the form
     *
     * @return array
     */
    protected function build(): array
    {
        return [
            'email' => '[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}',
        ];
    }
}
