<?php

declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use gift\core\application\usecases\CatalogueServiceInterface;
use gift\core\application\usecases\CatalogueService;

class GetCoffretTypesByThemeAction extends AbstractAction
{
    private CatalogueServiceInterface $catalogueService;

    public function __construct()
    {
        $this->catalogueService = new CatalogueService();
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $themesArray = $this->catalogueService->getThemesCoffrets();
            $view = Twig::fromRequest($request);
            return $view->render($response, 'liste_coffrets_par_theme.html.twig', [
                'themes' => $themesArray
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}