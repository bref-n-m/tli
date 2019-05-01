<?php

namespace App\Auth;


class Hasher
{
    /**
     * @param $password
     * @param $salt
     *
     * @return string
     */
    public function hash($password, $salt)
    {
        return hash(
            'sha256',
            hash(
                'sha256',
                $salt
            )
            . "-|-$password"
        );
    }
}
