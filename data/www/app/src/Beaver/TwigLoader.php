<?php

namespace Beaver;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigLoader
{
    const PUBLIC_DIRECTORY = 'public'.DIRECTORY_SEPARATOR;

    /** @var Environment */
    private $twig;

    /**
     * TwigLoader constructor.
     *
     * @param array $templateDirectories
     * @param Router $router
     */
    public function __construct(array $templateDirectories, Router $router)
    {
        $loader = new FilesystemLoader($templateDirectories);
        $this->twig = new Environment($loader);

        // asset function
        $this->twig->addFunction(new \Twig\TwigFunction('asset', function ($asset) {
            return sprintf(
                TwigLoader::PUBLIC_DIRECTORY.'%s',
                ltrim($asset, '/')
            );
        }));

        // path function
        $this->twig->addFunction(new \Twig\TwigFunction('path', function ($path, $parameters = []) use ($router) {
            return $router->generatePath($path, $parameters);
        }));
    }

    /**
     * @return Environment
     */
    public function getTwig(): Environment
    {
        return $this->twig;
    }
}
