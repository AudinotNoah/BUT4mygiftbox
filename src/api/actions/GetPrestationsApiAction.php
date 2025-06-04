<?php

declare(strict_types=1);

namespace gift\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use gift\core\application\usecases\CatalogueServiceInterface;
use gift\core\application\usecases\CatalogueService;

class GetPrestationsApiAction
{
    private CatalogueServiceInterface $catalogueService;

    public function __construct()
    {
        $this->catalogueService = new CatalogueService();
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $prestations = $this->catalogueService->getToutesPrestationsAvecDetails();

            $formattedPrestations = [];
            foreach ($prestations as $prestation) {
                $formattedPrestations[] = [
                    'prestation' => [
                        'id' => $prestation['id'],
                        'libelle' => $prestation['libelle'],
                        'description' => $prestation['description'],
                        'tarif' => $prestation['tarif'],
                        'unite' => $prestation['unite'] ?? null,
                        'img' => $prestation['img'] ?? null,
                        'categorie_id' => $prestation['cat_id'], 
                        'categorie_libelle' => $prestation['categorie']['libelle'] ?? null
                    ],
                ];
            }

            $data = [
                'type' => 'collection',
                'count' => count($formattedPrestations),
                'prestations' => $formattedPrestations
            ];

            $responseBody = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $response->getBody()->write($responseBody);

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);

        } catch (\Exception $e) {
            $errorData = [
                'type' => 'error',
                'message' => "Erreur lors de la récupération de la liste des prestations: " . $e->getMessage()
            ];
            $response->getBody()->write(json_encode($errorData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}