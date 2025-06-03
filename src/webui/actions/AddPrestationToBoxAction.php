<?php
declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;
use gift\core\application\usecases\GestionBoxService;
use gift\core\application\usecases\GestionBoxServiceInterface;
use gift\core\domain\exceptions\BoxNotFoundException;

class AddPrestationToBoxAction extends AbstractAction
{
    private GestionBoxServiceInterface $gestionBoxService;

    public function __construct()
    {
        $this->gestionBoxService = new GestionBoxService();
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();
        $prestationId = $data['id'] ?? throw new HttpBadRequestException($request, "ID prestation manquant");
        $quantity = (int) ($data['quantity'] ?? 1);

        $currentBox = $_SESSION['current_box']['id'] ?? null;
        if (!$currentBox) {
            throw new HttpBadRequestException($request, "Aucune box en session");
        }

        try {
            $boxArray = $this->gestionBoxService->ajouterPrestationABox($currentBox, $prestationId, $quantity);
            $_SESSION['current_box'] = $boxArray;

            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            $url = $routeParser->urlFor('view_current_box');

            return $response
                ->withHeader('Location', $url)
                ->withStatus(302);
        } catch (BoxNotFoundException $e) {
            throw new HttpNotFoundException($request, $e->getMessage(), $e);
        }
    }
}