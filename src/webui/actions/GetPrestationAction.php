<?php

declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Slim\Views\Twig;
use gift\core\application\usecases\CatalogueServiceInterface;
use gift\core\application\usecases\CatalogueService;
use gift\core\domain\exceptions\PrestationNotFoundException;

class GetPrestationAction extends AbstractAction
{
    private CatalogueServiceInterface $catalogueService;

    public function __construct()
    {
        $this->catalogueService = new CatalogueService();
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        
        try {
                        
            
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            $prestationId = $request->getQueryParams()['id'] ?? null;
            if (!$prestationId) {
                throw new HttpBadRequestException($request, "ID prestation manquant");
            }

            $prestationArray = $this->catalogueService->getPrestationById($prestationId);
            
            $view = Twig::fromRequest($request);
            return $view->render($response, 'prestation.html.twig', [
                'prestation' => $prestationArray,
                'session' => $_SESSION
            ]);
            
        } catch (PrestationNotFoundException $e) {
            throw new HttpNotFoundException($request, $e->getMessage(), $e);
        }
    }
}