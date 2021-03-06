<?php

namespace App\Entity;

class Pathology
{
    /** @var int */
    private $idP;

    /** @var string */
    private $mer;

    /** @var string */
    private $type;

    /** @var string */
    private $desc;

    public function __construct(int $idP, string $mer, string $type, string $desc)
    {
        $this->idP = $idP;
        $this->mer = $mer;
        $this->type = $type;
        $this->desc = $desc;
    }

    /**
     * @return int
     */
    public function getIdP(): int
    {
        return $this->idP;
    }

    /**
     * @return string
     */
    public function getMer(): string
    {
        return $this->mer;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getDesc(): string
    {
        return $this->desc;
    }
}
