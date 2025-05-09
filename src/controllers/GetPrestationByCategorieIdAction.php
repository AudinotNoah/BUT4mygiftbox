<?php

declare(strict_types=1);

namespace gift\appli\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetPrestationByCategorieIdAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $categorieId = $args['id'] ?? null;

        $html = "<h1>Prestations pour la Catégorie ID: " . htmlspecialchars((string)$categorieId) . " (Action)</h1>";
        $html .= "<p>à faire</p>";
        $html .= "<p><a href=\"/categories\">Retour aux catégories</a></p>";

        $response->getBody()->write($html);
        return $response;
    }
}