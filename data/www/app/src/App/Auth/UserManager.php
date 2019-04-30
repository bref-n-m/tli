<?php

namespace App\Auth;

use App\Repository\UserRepository;

class UserManager
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $userParams): bool
    {
        if (!($userParams['password'] === $userParams['password_confirm'])) {
            return false;
        }

        // Remove the confirmed password from the user params
        unset($userParams['password_confirm']);

        return $this->userRepository->insert($userParams);
    }
}
