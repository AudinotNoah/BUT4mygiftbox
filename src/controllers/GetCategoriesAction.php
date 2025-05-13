<?php

declare(strict_types=1);

namespace gift\appli\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use gift\appli\models\Categorie;
use Slim\Views\Twig;

class GetCategoriesAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $categories = Categorie::orderBy('libelle', 'asc')->get();
            $view = Twig::fromRequest($request);
            return $view->render($response, 'liste_categories.html.twig', [
                'categories' => $categories
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}