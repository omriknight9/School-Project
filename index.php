<?php

session_start();

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

$config = [];
$config['displayErrorDetails'] = true;

$app = new \Slim\App(['settings' => $config]);

$container = $app->getContainer();
// Register Twig View helper
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig('views');
    
    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new \Slim\Views\TwigExtension($c['router'], $basePath));

    return $view;
};

require 'routes.php';
$app->run();