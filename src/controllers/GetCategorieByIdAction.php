<?php

declare(strict_types=1);

namespace gift\appli\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetCategorieByIdAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];

        $categorieData = null;
        switch ($id) {
            case 1:
                $categorieData = ['id' => 1, 'libelle' => 'Restauration', 'description' => 'Découvrez nos offres de restauration.'];
                break;
            case 2:
                $categorieData = ['id' => 2, 'libelle' => 'Hébergement', 'description' => 'Trouvez l\'hébergement de vos rêves.'];
                break;
            case 3:
                $categorieData = ['id' => 3, 'libelle' => 'Bien-être', 'description' => 'Prenez soin de vous avec nos prestations bien-être.'];
                break;
        }

        $html = "";
        if ($categorieData) {
            $html .= "<h1>Catégorie (Action Class): " . htmlspecialchars($categorieData['libelle']) . "</h1>";
            $html .= "<p><strong>ID :</strong> " . htmlspecialchars((string)$categorieData['id']) . "</p>";
            $html .= "<p><strong>Description :</strong> " . htmlspecialchars($categorieData['description']) . "</p>";
        } else {
            $html .= "<h1>Catégorie non trouvée (Action Class)</h1>";
            $html .= "<p>Désolé, la catégorie avec l'ID " . htmlspecialchars((string)$id) . " n'existe pas.</p>";
        }
        $html .= "<p><a href=\"/categories\">Retour à la liste des catégories</a></p>";

        $response->getBody()->write($html);
        return $response;
    }
}