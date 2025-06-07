<?php

declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Slim\Exception\HttpNotFoundException;
use gift\core\application\usecases\BoxServiceInterface;
use gift\core\application\usecases\BoxService;
use gift\core\domain\exceptions\BoxNotFoundException;

class ViewBoxByTokenAction extends AbstractAction
{
    private BoxServiceInterface $boxService;

    public function __construct()
    {
        $this->boxService = new BoxService();
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $token = $args['token'] ?? null;
        if ($token === null) {
            throw new HttpNotFoundException($request, "Token de la box manquant.");
        }

        try {
            $boxData = $this->boxService->getBoxByToken($token);
            $user = $_SESSION['user'] ?? null;

            if ($user && isset($boxData['createur_id']) && $user['id'] === $boxData['createur_id'] && $boxData['statut'] === 1) {
                $_SESSION['current_box'] = $boxData;
            }

            $total = 0;
            if (isset($boxData['prestations'])) {
                foreach ($boxData['prestations'] as $prestation) {
                    $total += $prestation['tarif'] * $prestation['pivot']['quantite'];
                }
            }

            $isPrintMode = isset($request->getQueryParams()['print']);

            $view = Twig::fromRequest($request);
            return $view->render($response, 'view_box.html.twig', [
                'box' => $boxData,
                'is_gift_mode' => (bool)$boxData['kdo'],
                'is_print_mode' => $isPrintMode,
                'user' => $user,
                'total' => $total,
                'is_current_box_owner' => ($user && isset($boxData['createur_id']) && $user['id'] === $boxData['createur_id'])
            ]);
        } catch (BoxNotFoundException $e) {
            throw new HttpNotFoundException($request, $e->getMessage(), $e);
        }
    }
}