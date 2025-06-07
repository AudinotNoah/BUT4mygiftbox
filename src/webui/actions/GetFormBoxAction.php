<?php

declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Routing\RouteContext;
use gift\core\application\usecases\GestionBoxService;
use gift\webui\providers\CsrfTokenProvider;

class GetFormBoxAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $newBoxData = $request->getParsedBody();

            $csrfToken = $newBoxData['csrf'] ?? '';
            (new CsrfTokenProvider())->check($csrfToken);

            $user = $_SESSION['user'] ?? throw new HttpUnauthorizedException($request, 'Utilisateur non connecté.');
            $userId = $user['id'];

            $libelleNettoye = filter_var($newBoxData['libelle'], FILTER_SANITIZE_SPECIAL_CHARS);
            $descriptionNettoyee = filter_var($newBoxData['description'], FILTER_SANITIZE_SPECIAL_CHARS);
            $messageKdoNettoye = filter_var($newBoxData['gift_message'], FILTER_SANITIZE_SPECIAL_CHARS);

            if ($libelleNettoye !== $newBoxData['libelle'] ||
                $descriptionNettoyee !== $newBoxData['description'] ||
                $messageKdoNettoye !== $newBoxData['gift_message']) {
            }

            $isCadeau = isset($newBoxData['is_gift']) && $newBoxData['is_gift'] === 'on' ? 1 : 0;

            $dataPourService = [
                'libelle' => $libelleNettoye,
                'description' => $descriptionNettoyee,
                'isCadeau' => $isCadeau,
                'message_kdo' => $isCadeau ? $messageKdoNettoye : null,
            ];

            $gestionBoxService = new GestionBoxService();
            $coffretId = $newBoxData['from_coffret_id'] ?? null;

            if ($coffretId && is_numeric($coffretId)) {
                print("dait");
                die();
                $box = $gestionBoxService->creerBoxDepuisType($userId, (int)$coffretId, $dataPourService);
            } else {
                $box = $gestionBoxService->creerBoxVide($userId, $dataPourService);
            }

            $_SESSION['current_box'] = $box;

            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            
            $url = $routeParser->urlFor('view_current_box');

            return $response
                ->withHeader('Location', $url)
                ->withStatus(302);

        } catch (\Exception $e) {
            throw new HttpNotFoundException($request, "Erreur lors de la création de la box. Veuillez réessayer.");
        }
    }
}