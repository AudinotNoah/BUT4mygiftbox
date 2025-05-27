<?php

// declare(strict_types=1);

// namespace gift\webui\actions;

// use Psr\Http\Message\ResponseInterface as Response;
// use Psr\Http\Message\ServerRequestInterface as Request;
// use Slim\Views\Twig;
// use gift\core\application\usecases\BoxServiceInterface;
// use gift\core\application\usecases\BoxService;

// class ViewCurrentBoxAction extends AbstractAction
// {
//     private BoxServiceInterface $boxService;

//     public function __construct()
//     {
//         $this->boxService = new BoxService();
//     }

//     public function __invoke(Request $request, Response $response, array $args): Response
//     {
//         $userId = $_SESSION['user_id'] ?? null; // Assurez-vous que l'utilisateur est connecté
//         if (!$userId) {
//             return $response->withHeader('Location', '/login')->withStatus(302);
//         }

//         // Récupérer la box en cours de construction
//         $box = $this->boxService->getCurrentBoxByUserId($userId);

//         if (!$box) {
//             $view = Twig::fromRequest($request);
//             return $view->render($response, 'box_empty.html.twig', []);
//         }

//         // Récupérer les prestations associées à la box
//         $prestations = $this->boxService->getPrestationsByBoxId($box['id']);

//         $view = Twig::fromRequest($request);
//         return $view->render($response, 'view_box.html.twig', [
//             'box' => $box,
//             'prestations' => $prestations,
//         ]);
//     }
// }

declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class ViewCurrentBoxAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'view_box.html.twig', [
            'box' => [], // Remplacez par les données réelles de la box
        ]);
    }
}