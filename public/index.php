<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Slim\App;

$app = AppFactory::create();


$routesFilePath = __DIR__ . '/../src/conf/routes.php';

$app = (require_once $routesFilePath)($app);


$app->run();
