<?php

declare(strict_types=1);

namespace gift\appli\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetPrestationAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $queryParams = $request->getQueryParams();
        $prestationId = $queryParams['id'] ?? null;

        $html = "";
        if ($prestationId === null) {
            $html = "<h1>Erreur (Action Class)</h1><p>Le paramètre 'id' est manquant dans l'URL pour afficher la prestation.</p>";
        } else {
            $prestationData = null;
            if ($prestationId === 'prest123') {
                $prestationData = [
                    'id' => 'prest123',
                    'libelle' => 'Dîner Gastronomique',
                    'description' => 'Un repas inoubliable dans un cadre exceptionnel.',
                    'tarif' => '75.00 EUR'
                ];
            } elseif ($prestationId === 'spa001') {
                 $prestationData = [
                    'id' => 'spa001',
                    'libelle' => 'Journée Spa Détente',
                    'description' => 'Accès complet au spa, massage et soins.',
                    'tarif' => '120.00 EUR'
                ];
            }

            if ($prestationData) {
                $html .= "<h1>Prestation (Action Class): " . htmlspecialchars($prestationData['libelle']) . "</h1>";
                $html .= "<p><strong>ID :</strong> " . htmlspecialchars($prestationData['id']) . "</p>";
                $html .= "<p><strong>Description :</strong> " . htmlspecialchars($prestationData['description']) . "</p>";
                $html .= "<p><strong>Tarif :</strong> " . htmlspecialchars($prestationData['tarif']) . "</p>";
            } else {
                $html .= "<h1>Prestation non trouvée (Action Class)</h1>";
                $html .= "<p>Désolé, la prestation avec l'ID '" . htmlspecialchars($prestationId) . "' n'a pas été trouvée.</p>";
            }
        }
        $html .= "<p><a href=\"/categories\">Retour à l'accueil (simulé)</a></p>";

        $response->getBody()->write($html);
        return $response;
    }
}