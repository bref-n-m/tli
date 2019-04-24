<?php

namespace Beaver;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigLoader
{
    /** @var Environment */
    private $twig;

    /**
     * TwigLoader constructor.
     *
     * @param array $templateDirectories
     */
    public function __construct(array $templateDirectories)
    {
        $loader = new FilesystemLoader($templateDirectories);
        $this->twig = new Environment($loader);
    }

    /**
     * @return Environment
     */
    public function getTwig(): Environment
    {
        return $this->twig;
    }
}
