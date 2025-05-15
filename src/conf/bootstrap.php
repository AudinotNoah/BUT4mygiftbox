<?php

declare(strict_types=1);


use Slim\Factory\AppFactory;
use gift\appli\utils\Eloquent; 
use Slim\App;
use Slim\Views\Twig;           
use Slim\Views\TwigMiddleware;

try {
   
    Eloquent::init(__DIR__ . '/conf.ini');
} catch (\Exception $e) {
    echo("FATAL: Erreur initialisation Eloquent dans bootstrap: " . $e->getMessage());
   
}

$app = AppFactory::create();


$twig = Twig::create(__DIR__ . '/../views', ['cache' => __DIR__ . '/../views/cache', 'auto_reload' => true]);
$app->addRoutingMiddleware();

$app->add(TwigMiddleware::create($app, $twig));

$app->setBasePath('/archi/giftbox.squelette/gift.appli/public');

$routesFilePath = __DIR__ . '/routes.php';

$app = (require_once $routesFilePath)($app);


$errorMiddleware = $app->addErrorMiddleware(true, true, true);



return $app;