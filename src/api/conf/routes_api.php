<?php

declare(strict_types=1);

use Slim\App;
use gift\api\actions\GetCategoriesApiAction;
use gift\api\actions\GetBoxByIdApiAction;
use gift\api\actions\GetPrestationsApiAction;
use gift\api\actions\GetPrestationsByCategorieApiAction;

return function (App $app): App {

    $app->group('/api', function (\Slim\Routing\RouteCollectorProxy $group) {

        $group->get('/categories', GetCategoriesApiAction::class)
              ->setName('api_liste_categories');

        $group->get('/box/{id}', GetBoxByIdApiAction::class) 
              ->setName('api_details_box');

        $group->get('/prestations', GetPrestationsApiAction::class)
              ->setName('api_liste_prestations');

        $group->get('/categories/{id}/prestations', GetPrestationsByCategorieApiAction::class)
              ->setName('api_prestations_par_categorie');

    });

    return $app;
};