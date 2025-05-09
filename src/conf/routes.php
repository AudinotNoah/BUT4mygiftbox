<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use gift\appli\controllers\GetCategoriesAction;
use gift\appli\controllers\GetCategorieByIdAction;
use gift\appli\controllers\GetPrestationAction;
use gift\appli\controllers\GetPrestationByCategorieIdAction;

return function (App $app): App {

    // Route par défaut
    $app->get('/', function (Request $request, Response $response): Response {
        $basePath = $request->getUri()->getPath();
    
        $html = "<h1>Bienvenue sur MyGiftBox.net</h1>";
        $html .= "<p><a href='" . $basePath . "categories'>Voir les catégories</a></p>";
    
        $response->getBody()->write($html);
        return $response;
    });

    // GET /categories
    $app->get('/categories', GetCategoriesAction::class);


    // GET /categorie/{id}
    $app->get('/categorie/{id}', GetCategorieByIdAction::class);


    // GET /prestation?id=xxxx
    $app->get('/prestation', GetPrestationAction::class);


    // GET /categorie/{id}/prestations
    $app->get('/categorie/{id}/prestations', GetPrestationByCategorieIdAction::class);

    return $app;
};