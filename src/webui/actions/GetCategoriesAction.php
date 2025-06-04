<?php

declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use gift\core\application\usecases\CatalogueServiceInterface;
use gift\core\application\usecases\CatalogueService;

use gift\webui\providers\CsrfTokenProvider;

class GetCategoriesAction extends AbstractAction
{
    private CatalogueServiceInterface $catalogueService;

    public function __construct()
    {
        $this->catalogueService = new CatalogueService();
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            // $token = CsrfTokenProvider::generate(); // pour test
            // print $token;
            $categoriesArray = $this->catalogueService->getCategories();
            $view = Twig::fromRequest($request);
            return $view->render($response, 'liste_categories.html.twig', [
                'categories' => $categoriesArray,
                'user' => $_SESSION['user'] ?? null
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}