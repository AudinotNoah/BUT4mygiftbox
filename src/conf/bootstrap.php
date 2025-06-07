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
    error_log("FATAL: Erreur initialisation Eloquent: " . $e->getMessage());
    die("Erreur critique lors de l'initialisation de la base de données. Veuillez vérifier les logs.");
}

$app = AppFactory::create();

$templatePath = __DIR__ . '/../webui/views';
$cachePath = __DIR__ . '/../webui/views/cache';
$twig = Twig::create($templatePath, ['cache' => $cachePath, 'auto_reload' => true]);


// 1. Instanciation de Messages
$flash = new \Slim\Flash\Messages($_SESSION);

// 2. Middleware pour démarrer la session et recharger le flash depuis $_SESSION
$app->add(function ($request, $handler) use ($flash) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    return $handler->handle($request);
});

// 3. Injection du flash dans Twig
$twig->getEnvironment()->addGlobal('flash', $flash);

$app->addRoutingMiddleware(); 
$app->add(TwigMiddleware::create($app, $twig));

$routesWebUIFilePath = __DIR__ . '/routes.php';
if (file_exists($routesWebUIFilePath)) {
    (require_once $routesWebUIFilePath)($app); 
} else {
    error_log("Fichier de routes WebUI introuvable: " . $routesWebUIFilePath);
}

$routesApiFilePath = __DIR__ . '/../api/conf/routes_api.php'; 
if (file_exists($routesApiFilePath)) {
    (require_once $routesApiFilePath)($app); 
} else {
    error_log("Fichier de routes API introuvable: " . $routesApiFilePath);
}


$app->addErrorMiddleware(true, true, true);

return $app;