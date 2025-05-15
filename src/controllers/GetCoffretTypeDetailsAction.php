<?php

declare(strict_types=1);

namespace gift\appli\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use gift\appli\models\CoffretType;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Slim\Exception\HttpNotFoundException;
use Slim\Views\Twig;

class GetCoffretTypeDetailsAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $coffretId = (int)($args['id'] ?? 0);
            $coffretType = CoffretType::with('prestations')->findOrFail($coffretId);

            $view = Twig::fromRequest($request);
            return $view->render($response, 'details_coffret_type.html.twig', [
                'coffretType' => $coffretType
            ]);
        } catch (ModelNotFoundException $e) {
            throw new HttpNotFoundException($request, "Coffret type non trouvé pour l'ID: " . htmlspecialchars((string)($args['id'] ?? 'inconnu')), $e);
        }
    }
}