<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Slim\App;

use gift\appli\utils\Eloquent;

try {
    Eloquent::init(__DIR__ . '/../src/conf/conf.ini');
} catch (\Exception $e) {
    echo ($e->getMessage());
}

$app = AppFactory::create();
// $app->addRoutingMiddleware();
$app->setBasePath('/archi/giftbox.squelette/gift.appli/public');

$routesFilePath = __DIR__ . '/../src/conf/routes.php';

$app = (require_once $routesFilePath)($app);


$app->run();
