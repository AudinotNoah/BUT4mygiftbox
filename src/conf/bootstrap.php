<?php

declare(strict_types=1);


use Slim\Factory\AppFactory;
use gift\appli\utils\Eloquent; 
use Slim\App;

try {
   
    Eloquent::init(__DIR__ . '/conf.ini');
} catch (\Exception $e) {
    echo("FATAL: Erreur initialisation Eloquent dans bootstrap: " . $e->getMessage());
   
}

$app = AppFactory::create();


$app->addRoutingMiddleware();

$app->setBasePath('/archi/giftbox.squelette/gift.appli/public');

$routesFilePath = __DIR__ . '/routes.php';

$app = (require_once $routesFilePath)($app);


$errorMiddleware = $app->addErrorMiddleware(true, true, true);



return $app;