<?php

declare(strict_types=1);

use Slim\App;
use gift\api\actions\GetCategoriesApiAction;
use gift\api\actions\GetBoxByIdApiAction;

return function (App $app): App {

    $app->group('/api', function (\Slim\Routing\RouteCollectorProxy $group) {

        $group->get('/categories', GetCategoriesApiAction::class)
              ->setName('api_liste_categories');

        $group->get('/box/{id}', GetBoxByIdApiAction::class) 
              ->setName('api_details_box');

    });

    return $app;
};