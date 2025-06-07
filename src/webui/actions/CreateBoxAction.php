<?php

declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;


use gift\webui\providers\CsrfTokenProvider;


class CreateBoxAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        if (!isset($_SESSION['user'])) {
            throw new HttpUnauthorizedException($request, "Vous devez être connecté pour créer une box.");
        }

        try {
            $csrfToken = CsrfTokenProvider::generate();
            $view = Twig::fromRequest($request);
            return $view->render($response, 'form_nouvelle_box.twig', [
                'csrf' => $csrfToken,
                'user' => $_SESSION['user']
            ]);
        } catch (\Exception $e) {
            throw new HttpNotFoundException($request, "Erreur lors de l'affichage du formulaire de création : " . $e->getMessage(), $e);
        }
    }
}