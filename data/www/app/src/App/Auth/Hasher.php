<?php

namespace App\Auth;

class Hasher
{
    const OPTIONS = ['cost' => 12];
    const HASH_ALGORITHM = PASSWORD_BCRYPT;

    /**
     * @param string $password
     * 
     * @return string
     */
    public function hash(string $password): string
    {
        return password_hash($password, self::HASH_ALGORITHM, self::OPTIONS);
    }
}
