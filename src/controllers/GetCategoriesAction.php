<?php

declare(strict_types=1);

namespace gift\appli\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use gift\appli\models\Categorie;

class GetCategoriesAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $categories = Categorie::all(); 

            $html = "<h1>Liste des Catégories (BDD)</h1>";
            if ($categories->isEmpty()) {
                $html .= "<p>Aucune catégorie trouvée.</p>";
            } else {
                $html .= "<ul>";
                foreach ($categories as $categorie) {
                    $urlCategorie = "categorie/" . $categorie->id;
                    $html .= "<li><a href=\"" . htmlspecialchars($urlCategorie) . "\">"
                           . htmlspecialchars($categorie->id . ' - ' . $categorie->libelle)
                           . "</a></li>";
                }
                $html .= "</ul>";
            }
        } catch (\Exception $e) {
            $html = $e->getMessage();
        }

        $response->getBody()->write($html);
        return $response;
    }
}