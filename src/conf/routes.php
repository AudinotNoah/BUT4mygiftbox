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

    $app->get('/categories', GetCategoriesAction::class)
        ->setName('liste_categories');

    $app->get('/categorie/{id}', GetCategorieByIdAction::class)
        ->setName('details_categorie');

    $app->get('/categorie/{id}/prestations', GetPrestationByCategorieIdAction::class)
        ->setName('prestations_par_categorie');

    $app->get('/prestation', GetPrestationAction::class) // Route pour /prestation?id=...
        ->setName('details_prestation');

    return $app;
};