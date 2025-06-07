<?php

declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Views\Twig;
use gift\core\application\usecases\BoxServiceInterface;
use gift\core\application\usecases\BoxService;

class GetUserBoxesAction extends AbstractAction
{
    private BoxServiceInterface $boxService;

    public function __construct()
    {
        $this->boxService = new BoxService();
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            throw new HttpUnauthorizedException($request, "Vous devez être connecté pour accéder à cette page.");
        }

        $userBoxes = $this->boxService->getBoxesByUserId($user['id']);

        $view = Twig::fromRequest($request);
        return $view->render($response, 'user_boxes_list.html.twig', [
            'boxes' => $userBoxes,
            'user' => $user
        ]);
    }
}