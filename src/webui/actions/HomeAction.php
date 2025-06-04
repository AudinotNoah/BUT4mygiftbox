<?php

declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class HomeAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'home.html.twig', [
            'message_accueil' => 'Bienvenue sur MyGiftBox.net !', 
            'user' => $_SESSION['user'] ?? null
        ]);
    }
}