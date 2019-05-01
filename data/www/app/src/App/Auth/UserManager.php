<?php

namespace App\Auth;

use App\Repository\UserRepository;

class UserManager
{

    /** @var UserRepository */
    private $userRepository;

    /** @var Hasher */
    private $hasher;

    public function __construct(UserRepository $userRepository, Hasher $hasher)
    {
        $this->userRepository = $userRepository;
        $this->hasher = $hasher;
    }

    /**
     * @param array $userParams
     *
     * @return bool
     */
    public function register(array $userParams): bool
    {
        $userParams = $this->formatData($userParams);

        return !$userParams ? false : $this->userRepository->insert($userParams);
    }

    /**
     * @param array $userParams
     *
     * @return array
     */
    private function formatData(array $userParams): ?array
    {
        // Test if the passwords are identical
        if (!($userParams['password'] === $userParams['password_confirm'])) {
            return null;
        }

        // Remove the confirmed password from the user params
        unset($userParams['password_confirm']);

        $userParams['password'] = $this->hasher->hash($userParams['password']);

        return $userParams;
    }
}
