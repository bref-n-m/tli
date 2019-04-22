<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'core/Autoloader.php';
\Core\Autoloader::register();

$path = dirname(__FILE__).DIRECTORY_SEPARATOR;
/** @var \Beaver\Kernel $kernel */
$kernel = new \Beaver\Kernel($path);

$request = new \Beaver\Request\Request();
$response = $kernel->handle($request);
$response->send();
