<?php

namespace App\Entity;

class SymptomPathology
{
    /** @var int */
    private $idS;

    /** @var int */
    private $idP;

    /** @var int */
    private $aggr;

    public function __construct(int $idS, int $idP, int $aggr)
    {
        $this->idS = $idS;
        $this->idP = $idP;
        $this->aggr = $aggr;
    }

    /**
     * @return int
     */
    public function getIdS(): int
    {
        return $this->idS;
    }

    /**
     * @return int
     */
    public function getIdP(): int
    {
        return $this->idP;
    }

    /**
     * @return int
     */
    public function getAggr(): int
    {
        return $this->aggr;
    }
}
