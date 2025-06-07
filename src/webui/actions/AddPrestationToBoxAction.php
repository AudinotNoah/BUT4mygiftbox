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
use Slim\Exception\HttpUnauthorizedException;
use gift\core\domain\exceptions\BoxAlreadyValidatedException;

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

        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            throw new HttpUnauthorizedException($request, "Vous devez être connecté pour ajouter une prestation.");
        }
        $userId = $user['id'];

        $currentBox = $_SESSION['current_box'] ?? null;
        if (!$currentBox) {
            throw new HttpBadRequestException($request, "Aucune box en cours de création. Veuillez en créer une d'abord.");
        }
        $boxId = $currentBox['id'];

        try {
            $boxArray = $this->gestionBoxService->ajouterPrestationABox($boxId, $prestationId, $quantity, $userId);

            $_SESSION['current_box'] = $boxArray;

            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            $url = $routeParser->urlFor('view_current_box');

            return $response
                ->withHeader('Location', $url)
                ->withStatus(302);
        } catch (BoxAlreadyValidatedException $e) {
            if (isset($GLOBALS['flash']) && $GLOBALS['flash'] instanceof \Slim\Flash\Messages) {
                $GLOBALS['flash']->addMessage('error', $e->getMessage());
            }

            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            $url = $routeParser->urlFor('view_current_box', [
                'token' => $_SESSION['current_box']['token'],
                'user'  => $_SESSION['user'] ?? null
            ]);

            return $response->withHeader('Location', $url)->withStatus(302);
        } catch (BoxNotFoundException | PrestationNotFoundException $e) {
            throw new HttpNotFoundException($request, $e->getMessage(), $e);
        } catch (\Exception $e) {
            throw new HttpBadRequestException($request, $e->getMessage(), $e);
        }
    }
}