<?php

declare(strict_types=1);

namespace gift\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use gift\core\application\usecases\CatalogueServiceInterface;
use gift\core\application\usecases\CatalogueService;

class GetCategoriesApiAction
{
    private CatalogueServiceInterface $catalogueService;

    public function __construct()
    {
        $this->catalogueService = new CatalogueService();
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $categoriesFromService = $this->catalogueService->getCategories();

            $formattedCategories = [];
            foreach ($categoriesFromService as $categorie) {
                $formattedCategories[] = [
                    'categorie' => [
                        'id' => $categorie['id'],
                        'libelle' => $categorie['libelle'],
                        'description' => $categorie['description'] ?? '',
                    ],
                    'links' => [
                        'self' => ['href' => '/api/categories/' . $categorie['id'] . '/']
                    ]
                ];
            }

            $data = [
                'type' => 'collection',
                'count' => count($formattedCategories),
                'categories' => $formattedCategories
            ];

            $responseBody = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $response->getBody()->write($responseBody);

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);

        } catch (\Exception $e) {
            $errorData = [
                'type' => 'error',
                'error_code' => $e->getCode() ?: 500, 
                'message' => "Erreur lors de la récupération des catégories : " . $e->getMessage()
            ];


            $responseBody = json_encode($errorData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $response->getBody()->write($responseBody);

            $statusCode = $e->getCode();

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus($statusCode);
        }
    }
}