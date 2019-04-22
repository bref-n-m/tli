<?php

namespace Core;

class Autoloader
{
    const CLASS_PATH = 'src' . DIRECTORY_SEPARATOR;

    /**
     * register this autoloader
     */
    static function register()
    {
        spl_autoload_register([__CLASS__, 'autoload']);
    }

    /**
     * Require the file related to the class
     *
     * @param $class string class that need to be loaded
     */
    static function autoload($class)
    {
        $file = implode(DIRECTORY_SEPARATOR, explode('\\', $class));
        require self::CLASS_PATH.$file.'.php';
    }
}
