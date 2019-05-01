<?php

namespace App\Auth;


use App\Repository\UserRepository;
use Beaver\Request\Request;

class Authenticator
{
    /** @var UserRepository */
    private $userRepository;

    /** @var Hasher */
    private $hasher;

    /** @var Request */
    private $request;

    /**
     * Authenticator constructor.
     *
     * @param UserRepository $userRepository
     * @param Hasher $hasher
     * @param Request $request
     */
    public function __construct(UserRepository $userRepository, Hasher $hasher, Request $request)
    {
        $this->userRepository = $userRepository;
        $this->hasher = $hasher;
        $this->request = $request;
    }
}
