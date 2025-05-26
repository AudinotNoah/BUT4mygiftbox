<?php

declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Slim\Exception\HttpNotFoundException;
use gift\core\application\usecases\BoxServiceInterface;
use gift\core\application\usecases\BoxService;
use gift\core\domain\exceptions\BoxNotFoundException;

class ViewBoxByTokenAction extends AbstractAction
{
    private BoxServiceInterface $boxService;

    public function __construct() 
    {
        $this->boxService = new BoxService();
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $token = $args['token'] ?? null;

        if ($token === null) {
            throw new HttpNotFoundException($request, "Token de la box manquant.");
        }

        try {
            $boxData = $this->boxService->getBoxByToken($token);

            $isPrintMode = isset($request->getQueryParams()['print']);

            $view = Twig::fromRequest($request);
            return $view->render($response, 'view_box.html.twig', [
                'box' => $boxData,
                'is_gift_mode' => (bool)$boxData['kdo'],
                'is_print_mode' => $isPrintMode
            ]);

        } catch (BoxNotFoundException $e) {
            throw new HttpNotFoundException($request, $e->getMessage(), $e);
        }
    }
}