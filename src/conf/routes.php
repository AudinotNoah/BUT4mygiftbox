<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function (App $app): App {

    // Route par défaut pour la racine
    $app->get('/', function (Request $request, Response $response): Response {
        $html = "<h1>Bienvenue sur MyGiftBox.net</h1>";
        $html .= "<p><a href='/categories'>Voir les catégories</a></p>";
        $response->getBody()->write($html);
        return $response;
    });

    // Route 1: Affichage des catégories
    // GET /categories
    $app->get('/categories', function (Request $request, Response $response, array $args): Response {
        // pour test
        $categories = [
            ['id' => 1, 'libelle' => 'Restauration', 'url' => '/categorie/1'],
            ['id' => 2, 'libelle' => 'Hébergement', 'url' => '/categorie/2'],
            ['id' => 3, 'libelle' => 'Bien-être', 'url' => '/categorie/3'],
        ];

        $html = "<h1>Liste des Catégories</h1><ul>";
        foreach ($categories as $categorie) {
            $html .= "<li><a href=\"" . htmlspecialchars($categorie['url']) . "\">" . htmlspecialchars($categorie['id'] . ' - ' . $categorie['libelle']) . "</a></li>";
        }
        $html .= "</ul>";

        $response->getBody()->write($html);
        return $response;
    });


    // Route 2: Affichage d'une catégorie
    // GET /categorie/{id}
    $app->get('/categorie/{id}', function (Request $request, Response $response, array $args): Response {
        $id = (int)$args['id'];

        $categorieData = null;
        switch ($id) {
            case 1:
                $categorieData = ['id' => 1, 'libelle' => 'Restauration', 'description' => 'Découvrez nos offres de restauration.'];
                break;
            case 2:
                $categorieData = ['id' => 2, 'libelle' => 'Hébergement', 'description' => 'Trouvez l\'hébergement de vos rêves.'];
                break;
            case 3:
                $categorieData = ['id' => 3, 'libelle' => 'Bien-être', 'description' => 'Prenez soin de vous avec nos prestations bien-être.'];
                break;
        }

        $html = "";
        if ($categorieData) {
            $html .= "<h1>Catégorie : " . htmlspecialchars($categorieData['libelle']) . "</h1>";
            $html .= "<p><strong>ID :</strong> " . htmlspecialchars((string)$categorieData['id']) . "</p>";
            $html .= "<p><strong>Description :</strong> " . htmlspecialchars($categorieData['description']) . "</p>";
        } else {
            $html .= "<h1>Catégorie non trouvée</h1>";
            $html .= "<p>Désolé, la catégorie avec l'ID " . htmlspecialchars((string)$id) . " n'existe pas.</p>";
            return $response->withStatus(404);
        }
        $html .= "<p><a href=\"/categories\">Retour à la liste des catégories</a></p>";


        $response->getBody()->write($html);
        return $response;
    });


    // Route 3: Affichage d'une prestation
    // GET /prestation?id=xxxx
    $app->get('/prestation', function (Request $request, Response $response, array $args): Response {
        $queryParams = $request->getQueryParams();
        $prestationId = $queryParams['id'] ?? null;

        $html = "";
        if ($prestationId === null) {
            $html = "<h1>Erreur</h1><p>Le paramètre 'id' est manquant dans l'URL pour afficher la prestation.</p>";
            $response = $response->withStatus(400);
        } else {
            $prestationData = null;
            if ($prestationId === 'prest123') {
                $prestationData = [
                    'id' => 'prest123',
                    'libelle' => 'Dîner Gastronomique',
                    'description' => 'Un repas inoubliable dans un cadre exceptionnel.',
                    'tarif' => '75.00 EUR'
                ];
            } elseif ($prestationId === 'spa001') {
                 $prestationData = [
                    'id' => 'spa001',
                    'libelle' => 'Journée Spa Détente',
                    'description' => 'Accès complet au spa, massage et soins.',
                    'tarif' => '120.00 EUR'
                ];
            }

            if ($prestationData) {
                $html .= "<h1>Prestation : " . htmlspecialchars($prestationData['libelle']) . "</h1>";
                $html .= "<p><strong>ID :</strong> " . htmlspecialchars($prestationData['id']) . "</p>";
                $html .= "<p><strong>Description :</strong> " . htmlspecialchars($prestationData['description']) . "</p>";
                $html .= "<p><strong>Tarif :</strong> " . htmlspecialchars($prestationData['tarif']) . "</p>";
            } else {
                $html .= "<h1>Prestation non trouvée</h1>";
                $html .= "<p>Désolé, la prestation avec l'ID '" . htmlspecialchars($prestationId) . "' n'a pas été trouvée.</p>";
                $response = $response->withStatus(404);
            }
        }
        $html .= "<p><a href=\"/categories\">Retour à l'accueil (simulé)</a></p>";

        $response->getBody()->write($html);
        return $response;
    });

    return $app;
};