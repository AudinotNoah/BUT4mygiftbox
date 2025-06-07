<?php

declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ServerRequestInterface as Request;

use gift\webui\providers\AuthnProviderInterface;
use gift\webui\providers\AuthnProvider;
use Psr\Http\Message\ResponseInterface as Response;

class LogoutAction extends AbstractAction
{
    private AuthnProviderInterface $authnProvider;

    public function __construct()
    {
        $this->authnProvider =new AuthnProvider();
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $this->authnProvider->logout();

            if (isset($_SESSION['current_box'])) {
                unset($_SESSION['current_box']);
            }
            
            return $response->withHeader('Location', '/')->withStatus(302);
        } catch (\Exception $e) {
            return $response->withStatus(500)->withJson(['error' => 'Erreur lors de la déconnexion : ' . $e->getMessage()]);
        }
    }
}