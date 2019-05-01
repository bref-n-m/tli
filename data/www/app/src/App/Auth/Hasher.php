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

    /**
     * @param string $hashedPassword
     * @param string $password
     *
     * @return bool
     */
    public function verrify(string $hashedPassword, string $password): bool
    {
        return password_verify($password, $hashedPassword);
    }
}
