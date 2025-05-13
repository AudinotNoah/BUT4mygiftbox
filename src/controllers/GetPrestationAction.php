<?php

declare(strict_types=1);

namespace gift\appli\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use gift\appli\models\Prestation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

class GetPrestationAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $queryParams = $request->getQueryParams();
        $prestationId = $queryParams['id'] ?? null;

        if ($prestationId === null) {
            throw new HttpBadRequestException($request, "Paramètre 'id' requis pour la prestation.");
        }
        try {
            $prestation = Prestation::findOrFail($prestationId);

            $html = "<h1>Prestation (BDD): " . htmlspecialchars($prestation->libelle) . "</h1>";
            $html .= "<p><strong>ID :</strong> " . htmlspecialchars($prestation->id) . "</p>";
            $html .= "<p><strong>Description :</strong> " . htmlspecialchars($prestation->description) . "</p>";
            $html .= "<p><strong>Tarif :</strong> " . htmlspecialchars((string)$prestation->tarif) . " (" . htmlspecialchars($prestation->unite ?? '') . ")</p>";
            $html .= "<p><a href=\"/categories\">Retour à l'accueil</a></p>";

            $response->getBody()->write($html);
            return $response;

        } catch (ModelNotFoundException $e) {
            throw new HttpNotFoundException($request, "Prestation non trouvée pour l'ID: " . htmlspecialchars($prestationId), $e);
        }
    }
}