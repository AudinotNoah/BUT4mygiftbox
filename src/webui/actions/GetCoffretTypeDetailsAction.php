<?php

declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Slim\Views\Twig;
use gift\core\application\usecases\CatalogueServiceInterface;
use gift\core\application\usecases\CatalogueService;
use gift\core\domain\exceptions\CoffretTypeNotFoundException;

class GetCoffretTypeDetailsAction extends AbstractAction
{
    private CatalogueServiceInterface $catalogueService;

    public function __construct()
    {
        $this->catalogueService = new CatalogueService();
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $coffretId = (int)($args['id'] ?? 0);
            $coffretTypeArray = $this->catalogueService->getCoffretById($coffretId);

            $view = Twig::fromRequest($request);
            return $view->render($response, 'details_coffret_type.html.twig', [
                'coffretType' => $coffretTypeArray
            ]);
        } catch (CoffretTypeNotFoundException $e) {
            throw new HttpNotFoundException($request, $e->getMessage(), $e);
        }
    }
}