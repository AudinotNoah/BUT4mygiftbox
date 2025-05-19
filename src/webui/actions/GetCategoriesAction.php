<?php

declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use gift\core\domain\entities\Categorie;
use Slim\Views\Twig;

class GetCategoriesAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $categories = Categorie::orderBy('libelle', 'desc')->get();
            $view = Twig::fromRequest($request);
            return $view->render($response, 'liste_categories.html.twig', [
                'categories' => $categories
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}