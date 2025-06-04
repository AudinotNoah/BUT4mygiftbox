<?php

declare(strict_types=1);

namespace gift\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use gift\core\application\usecases\CatalogueServiceInterface;
use gift\core\application\usecases\CatalogueService;
use gift\core\domain\exceptions\CategorieNotFoundException;
use Slim\Exception\HttpNotFoundException;

class GetPrestationsByCategorieApiAction
{
    private CatalogueServiceInterface $catalogueService;

    public function __construct()
    {
        $this->catalogueService = new CatalogueService();
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $categorieId = (int)($args['id'] ?? 0);

        try {
            $categorieInfo = $this->catalogueService->getCategorieById($categorieId); 

            $prestations = $this->catalogueService->getPrestationsbyCategorie($categorieId);

            $formattedPrestations = [];
            foreach ($prestations as $prestation) {
                $formattedPrestations[] = [
                    'prestation' => [
                        'id' => $prestation['id'],
                        'libelle' => $prestation['libelle'],
                        'description' => $prestation['description'],
                        'tarif' => $prestation['tarif'],
                        'unite' => $prestation['unite'] ?? null,
                        'img' => $prestation['img'] ?? null
                    ],
                ];
            }

            $categorieInfo = $this->catalogueService->getCategorieById($categorieId);


            $data = [
                'type' => 'collection',
                'count' => count($formattedPrestations),
                'categorie' => [ 
                    'id' => $categorieInfo['id'],
                    'libelle' => $categorieInfo['libelle']
                ],
                'prestations' => $formattedPrestations
            ];

            $responseBody = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES| JSON_UNESCAPED_UNICODE);
            $response->getBody()->write($responseBody);

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);

        } catch (CategorieNotFoundException $e) {
            throw new HttpNotFoundException($request, "Catégorie non trouvée pour l'ID: " . htmlspecialchars((string)$categorieId), $e);
        } catch (\Exception $e) {
            $errorData = [
                'type' => 'error',
                'message' => "Erreur lors de la récupération des prestations pour la catégorie: " . $e->getMessage()
            ];
            $response->getBody()->write(json_encode($errorData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES| JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}