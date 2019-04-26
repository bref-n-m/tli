<?php

namespace App\Entity;

class User
{
    /** @var int */
    private $id;

    /** @var string */
    private $email;

    /** @var string */
    private $first_name;

    /** @var string */
    private $last_name;

    /** @var string */
    private $password;

    public function __construct(int $id, string $email, string $first_name, string $last_name, string $password)
    {
        $this->id = $id;
        $this->email = $email;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->password = $password;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->first_name;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->last_name;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
