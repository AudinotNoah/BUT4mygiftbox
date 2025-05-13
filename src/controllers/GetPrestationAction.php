<?php

declare(strict_types=1);

namespace gift\appli\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use gift\appli\models\Prestation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Slim\Views\Twig;


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
            $view = Twig::fromRequest($request);
            return $view->render($response, 'prestation.html.twig', [
                'prestation' => $prestation
            ]);
        } catch (ModelNotFoundException $e) {
            throw new HttpNotFoundException($request, "Prestation non trouvée pour l'ID: " . htmlspecialchars($prestationId), $e);
        }
    }
}