<?php

declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Routing\RouteContext;
use gift\core\application\usecases\GestionBoxServiceInterface;
use gift\core\application\usecases\GestionBoxService;

class ModifyPrestationQuantityAction extends AbstractAction
{
    private GestionBoxServiceInterface $gestionBoxService;

    public function __construct()
    {
        $this->gestionBoxService = new GestionBoxService();
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();
        $prestationId = $data['presta_id'] ?? throw new HttpBadRequestException($request, "ID de prestation manquant.");
        $newQuantity = (int)($data['quantity'] ?? 1);

        if ($newQuantity < 0) {
             throw new HttpBadRequestException($request, "La quantité ne peut pas être négative.");
        }

        $user = $_SESSION['user'] ?? throw new HttpUnauthorizedException($request, "Vous devez être connecté.");
        $box = $_SESSION['current_box'] ?? throw new HttpBadRequestException($request, "Aucune box en cours.");

        $updatedBox = $this->gestionBoxService->modifierQuantitePrestationDansBox($box['id'], $prestationId, $newQuantity, $user['id']);

        $_SESSION['current_box'] = $updatedBox;
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $url = $routeParser->urlFor('view_current_box');
        return $response->withHeader('Location', $url)->withStatus(302);
    }
}