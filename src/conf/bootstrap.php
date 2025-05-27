<?php

declare(strict_types=1);


use Slim\Factory\AppFactory;
use gift\infra\Eloquent; 
use Slim\App;
use Slim\Views\Twig;           
use Slim\Views\TwigMiddleware;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
   
    Eloquent::init(__DIR__ . '/conf.ini');
} catch (\Exception $e) {
    echo("Erreur initialisation Eloquent dans bootstrap: " . $e->getMessage());
   
}

$app = AppFactory::create();


$twig = Twig::create(__DIR__ . '/../webui/views', ['cache' => __DIR__ . '/../webui/views/cache', 'auto_reload' => true]);
$app->addRoutingMiddleware();

$app->add(TwigMiddleware::create($app, $twig));

// $app->setBasePath(basePath: '/archi/giftbox.squelette/gift.appli/public');

$routesFilePath = __DIR__ . '/routes.php';

$app = (require_once $routesFilePath)($app);


$errorMiddleware = $app->addErrorMiddleware(true, true, true);



return $app;