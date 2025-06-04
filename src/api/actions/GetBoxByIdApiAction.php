<?php

declare(strict_types=1);

namespace gift\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use gift\core\application\usecases\BoxServiceInterface;
use gift\core\application\usecases\BoxService;

use gift\core\domain\exceptions\BoxNotFoundException;
use Slim\Exception\HttpNotFoundException;

class GetBoxByIdApiAction
{
    private BoxServiceInterface $boxService;

    public function __construct()
    {
        $this->boxService = new BoxService();
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $tokenId = $args['id'];

        try {
            $boxDetails = $this->boxService->getBoxById($tokenId);

            $prestations = $this->boxService->getPrestationsByBoxId($tokenId);

            $data = [
                'type' => 'resource',
                'box' => [
                    'id' => $boxDetails['id'],
                    'libelle' => $boxDetails['libelle'],
                    'description' => $boxDetails['description'] ?? null,
                    'message_kdo' => $boxDetails['message_kdo'] ?? null,
                    'statut' => $boxDetails['statut'],
                    'prestations' => []
                ]
            ];

            foreach ($prestations as $presta) {
                $data['box']['prestations'][] = [
                    'libelle' => $presta['libelle'],
                    'description' => $presta['description'],
                    'contenu' => [
                        'box_id' => $boxDetails['id'],
                        'presta_id' => $presta['id'],
                        'quantite' => $presta['pivot']['quantite'] ?? 1
                    ]
                ];
            }

            $responseBody = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $response->getBody()->write($responseBody);

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);

        } catch (BoxNotFoundException $e) {
            throw new HttpNotFoundException($request, "Ressource Box non trouvée pour l'identifiant : " . htmlspecialchars($tokenId), $e);
        } catch (\Exception $e) {
            $errorData = [
                'type' => 'error',
                'message' => "Erreur serveur lors de la récupération de la box: " . $e->getMessage()
            ];

            $response->getBody()->write(json_encode($errorData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}
