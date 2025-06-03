<?php

declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use gift\core\application\usecases\BoxServiceInterface;
use gift\core\application\usecases\BoxService;

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
                return $view->render($response, 'box_empty.html.twig', ['liste_categories_url' => $this->router->urlFor('liste_categories')]);
            }

            $prestations = $this->boxService->getPrestationsByBoxId($box['id']);
            
            $total = 0;
            if (isset($box['prestations'])) {
                foreach ($box['prestations'] as $prestation) {
                    $total += $prestation['tarif'] * $prestation['pivot']['quantite'];
                }
            }

            $view = Twig::fromRequest($request);
            return $view->render($response, 'view_box.html.twig', [
                'box' => $box,
                'total' => $total
            ]);
        } catch (\Exception $e) {
            return $response->withHeader('Location', '/box/create')->withStatus(302);
        }
    }

    private function calculateTotal(array $prestations): float 
    {
        return array_reduce($prestations, function($total, $prestation) {
            return $total + ($prestation['tarif'] * $prestation['pivot']['quantite']);
        }, 0.0);
    }
}
