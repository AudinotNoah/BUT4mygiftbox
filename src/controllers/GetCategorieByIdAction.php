<?php

declare(strict_types=1);

namespace gift\appli\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use gift\appli\models\Categorie;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Slim\Exception\HttpNotFoundException;

class GetCategorieByIdAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $id = (int)$args['id'];
            $categorie = Categorie::findOrFail($id);

            $html = "<h1>Catégorie (BDD): " . htmlspecialchars($categorie->libelle) . "</h1>";
            $html .= "<p><strong>ID :</strong> " . htmlspecialchars((string)$categorie->id) . "</p>";
            $html .= "<p><strong>Description :</strong> " . htmlspecialchars($categorie->description ?? 'N/A') . "</p>";
            $html .= "<p><a href=\"/categories\">Retour à la liste des catégories</a></p>";

            $response->getBody()->write($html);
            return $response;

        } catch (ModelNotFoundException $e) {
            throw new HttpNotFoundException($request, "Catégorie non trouvée pour l'ID: " . htmlspecialchars((string)$args['id']), $e);
        }
    }
}