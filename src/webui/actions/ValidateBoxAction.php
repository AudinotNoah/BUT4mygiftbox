<?php

declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use gift\core\application\usecases\GestionBoxService;
use Slim\Routing\RouteContext;
use gift\core\domain\entities\Box;

class ValidateBoxAction extends AbstractAction
{
    private GestionBoxService $gestionBoxService;

    public function __construct()
    {
        $this->gestionBoxService = new GestionBoxService();
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $currentBox = $_SESSION['current_box'] ?? null;
            if (!$currentBox) {
                throw new HttpBadRequestException($request, "Aucune box en cours");
            }

            
            // Valider la box
            $boxArray = $this->gestionBoxService->validerBox($currentBox['id']);
            
            // Mettre à jour la session
            $_SESSION['current_box'] = $boxArray;
            
            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            $url = $routeParser->urlFor('view_current_box');

            // Rediriger vers la vue de la box
            return $response
                ->withHeader('Location', $url)
                ->withStatus(302);

        } catch (\Exception $e) {
            throw new HttpBadRequestException($request, $e->getMessage());
        }
    }
}