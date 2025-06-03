<?php

declare(strict_types=1);

use Slim\App;
use gift\webui\actions\GetCategoriesAction;
use gift\webui\actions\GetCategorieByIdAction;
use gift\webui\actions\GetPrestationAction;
use gift\webui\actions\GetPrestationByCategorieIdAction;
use gift\webui\actions\HomeAction;
use gift\webui\actions\GetCoffretTypesByThemeAction;
use gift\webui\actions\GetCoffretTypeDetailsAction;
use gift\webui\actions\ViewBoxByTokenAction;
use gift\webui\actions\CreateBoxAction;
use gift\webui\actions\GetFormBoxAction;
use gift\webui\actions\AddPrestationToBoxAction;
use gift\webui\actions\ViewCurrentBoxAction;

return function (App $app): App {

    // Route pour la page d'accueil
    $app->get('/', HomeAction::class)
        ->setName('home');                                         

    $app->get('/categories', GetCategoriesAction::class)
        ->setName('liste_categories');

    $app->get('/categorie/{id}', GetCategorieByIdAction::class)
        ->setName('details_categorie');

    $app->get('/categorie/{id}/prestations', GetPrestationByCategorieIdAction::class)
        ->setName('prestations_par_categorie');

    $app->get('/prestation', GetPrestationAction::class)
        ->setName('details_prestation');

    // Route pour lister les coffrets types par thème
    $app->get('/coffrets', GetCoffretTypesByThemeAction::class)
        ->setName('liste_coffrets_par_theme');

    // Route pour afficher les détails d'un coffret type
    $app->get('/coffret/{id}', GetCoffretTypeDetailsAction::class)
        ->setName('details_coffret_type');

    $app->get('/box/view/token={token}', ViewBoxByTokenAction::class) 
        ->setName('view_box_by_token');


    $app->get('/box/current', ViewCurrentBoxAction::class)
        ->setName('view_current_box');

    $app->get('/box/create', CreateBoxAction::class)
        ->setName('create_box');

    $app->post('/box/create', GetFormBoxAction::class) 
        ->setName('create_box_post');
    
    $app->post('/box/prestation/add', AddPrestationToBoxAction::class)
        ->setName('add_prestation_to_box');


    return $app;
};