<?php

declare(strict_types=1);

namespace gift\appli\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use gift\appli\models\Categorie;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Slim\Exception\HttpNotFoundException; 

class GetPrestationByCategorieIdAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $categorieId = (int)($args['id'] ?? 0);
            $categorie = Categorie::with('prestations')->findOrFail($categorieId);

            $html = "<h1>Prestations pour la Catégorie (BDD): " . htmlspecialchars($categorie->libelle) . "</h1>";
            if ($categorie->prestations->isEmpty()) {
                $html .= "<p>Aucune prestation trouvée pour cette catégorie.</p>";
            } else {
                $html .= "<ul>";
                foreach ($categorie->prestations as $prestation) {
                    $html .= "<li>"
                           . htmlspecialchars($prestation->libelle)
                           . " (ID: " . htmlspecialchars($prestation->id) . ")"
                           . "</li>";
                }
                $html .= "</ul>";
            }
            $html .= "<p><a href=\"/categories\">Retour aux catégories</a></p>";

            $response->getBody()->write($html);
            return $response;

        } catch (ModelNotFoundException $e) {
            throw new HttpNotFoundException($request, "Catégorie non trouvée pour l'ID: " . htmlspecialchars((string)$args['id']), $e);
        }
    }
}