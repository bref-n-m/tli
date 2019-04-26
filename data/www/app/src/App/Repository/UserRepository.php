<?php

namespace App\Repository;

use App\Entity\User;
use Beaver\Repository\AbstractRepository;

class UserRepository extends AbstractRepository
{
    public function getUserByEmailAndPassword(string $email, string $password): ?User
    {
        return $this->getOne(
            $this->getByRows(['email' => $email, 'password' => $password])
        );
    }
}
