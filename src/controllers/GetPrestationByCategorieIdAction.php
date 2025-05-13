<?php

declare(strict_types=1);

namespace gift\appli\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use gift\appli\models\Categorie;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Slim\Exception\HttpNotFoundException;
use Slim\Views\Twig;


class GetPrestationByCategorieIdAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $categorieId = (int)($args['id'] ?? 0);
            $categorie = Categorie::with('prestations')->findOrFail($categorieId); // Eager load prestations
            $view = Twig::fromRequest($request);
            return $view->render($response, 'categorie_prestations.html.twig', [
                'categorie' => $categorie
            ]);
        } catch (ModelNotFoundException $e) {
            throw new HttpNotFoundException($request, "Catégorie non trouvée pour l'ID: " . htmlspecialchars((string)($args['id'] ?? 'inconnu')), $e);
        }
    }
}