<?php

declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use gift\core\domain\entities\Theme;
use Slim\Views\Twig;

class GetCoffretTypesByThemeAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $themes = Theme::with('coffretTypes')->orderBy('libelle')->get();

        $view = Twig::fromRequest($request);
        return $view->render($response, 'liste_coffrets_par_theme.html.twig', [
            'themes' => $themes
        ]);
    }
}