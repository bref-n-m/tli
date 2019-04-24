<?php

namespace App\Entity;

class Symptom
{
    /** @var int */
    private $idS;

    /** @var string */
    private $desc;

    public function __construct(int $idS, string $desc)
    {
        $this->idS = $idS;
        $this->desc = $desc;
    }

    /**
     * @return int
     */
    public function getIdS(): int
    {
        return $this->idS;
    }

    /**
     * @return string
     */
    public function getDesc(): string
    {
        return $this->desc;
    }
}
