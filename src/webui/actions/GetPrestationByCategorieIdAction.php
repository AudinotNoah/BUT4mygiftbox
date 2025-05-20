<?php

declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Slim\Views\Twig;
use gift\core\application\usecases\CatalogueServiceInterface;
use gift\core\application\usecases\CatalogueService;
use gift\core\domain\exceptions\CategorieNotFoundException;


class GetPrestationByCategorieIdAction extends AbstractAction
{
    private CatalogueServiceInterface $catalogueService;

    public function __construct()
    {
        $this->catalogueService = new CatalogueService();
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $categorieId = (int)($args['id'] ?? 0);
            $categorieInfo = $this->catalogueService->getCategorieById($categorieId);
            $prestationsArray = $this->catalogueService->getPrestationsbyCategorie($categorieId);

            $view = Twig::fromRequest($request);
            return $view->render($response, 'categorie_prestations.html.twig', [
                'categorie' => $categorieInfo,
                'prestations' => $prestationsArray
            ]);
        } catch (CategorieNotFoundException $e) {
            throw new HttpNotFoundException($request, $e->getMessage(), $e);
        }
    }
}