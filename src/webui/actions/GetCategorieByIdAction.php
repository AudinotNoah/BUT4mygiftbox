<?php

declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Slim\Exception\HttpNotFoundException;

use gift\core\application\usecases\CatalogueServiceInterface;
use gift\core\application\usecases\CatalogueService;
use gift\core\domain\exceptions\CategorieNotFoundException;

class GetCategorieByIdAction extends AbstractAction
{
    private CatalogueServiceInterface $catalogueService;

    public function __construct()
    {
        $this->catalogueService = new CatalogueService();
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $id = (int)$args['id'];
            $categorieDataArray = $this->catalogueService->getCategorieById($id);
            $view = Twig::fromRequest($request);
            return $view->render($response, 'categorie.html.twig', [
                'categorie' => $categorieDataArray,
                'user' => $_SESSION['user'] ?? null
            ]);

        } catch (CategorieNotFoundException $e) {
            throw new HttpNotFoundException($request, $e->getMessage(), $e);
        }
    }
}