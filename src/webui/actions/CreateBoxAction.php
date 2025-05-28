<?php

declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Slim\Exception\HttpNotFoundException;

use gift\core\application\usecases\BoxServiceInterface;
use gift\core\application\usecases\BoxService;

use gift\webui\providers\CsrfTokenProvider;


class CreateBoxAction extends AbstractAction
{
    private BoxServiceInterface $boxService;

    public function __construct()
    {
        $this->boxService = new BoxService();
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $csrfToken = CsrfTokenProvider::generate();
            $view = Twig::fromRequest($request);
            return $view->render($response, 'form_nouvelle_box.twig', [
                'csrf' => $csrfToken,
            ]);
        } catch (\Exception $e) {
            throw new HttpNotFoundException($request, "Erreur lors de la création de la box : " . $e->getMessage(), $e);
        }

    }
}