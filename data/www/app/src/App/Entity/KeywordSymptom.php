<?php

namespace App\Entity;

class KeywordSymptom
{
    /** @var int */
    private $idK;

    /** @var int */
    private $idS;

    public function __construct(int $idK, int $idS)
    {
        $this->idK = $idK;
        $this->idS = $idS;
    }

    /**
     * @return int
     */
    public function getIdK(): int
    {
        return $this->idK;
    }

    /**
     * @return mixed
     */
    public function getIdS()
    {
        return $this->idS;
    }
}
