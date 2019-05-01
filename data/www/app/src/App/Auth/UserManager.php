<?php

namespace App\Auth;

use App\Repository\UserRepository;

class UserManager
{
    private $userRepository;

    /** @var Hasher */
    private $hasher;

    public function __construct(UserRepository $userRepository, Hasher $hasher)
    {
        $this->userRepository = $userRepository;
        $this->hasher = $hasher;
    }

    public function register(array $userParams): bool
    {
        if (!($userParams['password'] === $userParams['password_confirm'])) {
            return false;
        }

        // Remove the confirmed password from the user params
        unset($userParams['password_confirm']);

        $userParams['password'] = $this->hasher->hash($userParams['password']);

        return $userParams;
    }
}
