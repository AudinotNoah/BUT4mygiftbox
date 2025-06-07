<?php

declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use gift\core\application\usecases\BoxServiceInterface;
use gift\core\application\usecases\BoxService;
use Slim\Routing\RouteContext;


class ViewCurrentBoxAction extends AbstractAction
{
    private BoxServiceInterface $boxService;

    public function __construct()
    {
        $this->boxService = new BoxService();
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $box = $this->boxService->getCurrentBox();

            if (!$box) {
                $view = Twig::fromRequest($request);
                $routeParser = RouteContext::fromRequest($request)->getRouteParser();
                return $view->render($response, 'box_empty.html.twig', [
                    'create_box_url' => $routeParser->urlFor('create_box')
                ]);
            }

            $total = 0;
            if (isset($box['prestations'])) {
                foreach ($box['prestations'] as $prestation) {
                    $total += $prestation['tarif'] * $prestation['pivot']['quantite'];
                }
            }

            $user = $_SESSION['user'] ?? null;
            
            $isOwner = $user && isset($box['createur_id']) && $user['id'] === $box['createur_id'];

            $shareUrl = '';
            $isPrintMode = false;

            $view = Twig::fromRequest($request);
            return $view->render($response, 'view_box.html.twig', [
                'box' => $box,
                'total' => $total,
                'user' => $user,
                'is_current_box_owner' => $isOwner,
                'share_url' => $shareUrl,                 
                'is_print_mode' => $isPrintMode,          
                'is_gift_mode' => (bool)($box['kdo'] ?? false)
            ]);

        } catch (\Exception $e) {
            return $response->withHeader('Location', '/box/create')->withStatus(302);
        }
    }
}