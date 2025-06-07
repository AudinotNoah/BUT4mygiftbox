<?php

declare(strict_types=1);

namespace gift\webui\actions;

use gift\webui\providers\AuthnProviderInterface;
use gift\webui\providers\AuthnProvider;
use gift\webui\providers\CsrfTokenProvider;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

class SignInAction extends AbstractAction
{
    private AuthnProviderInterface $authnProvider;

    public function __construct()
    {
        $this->authnProvider = new AuthnProvider();
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $data = $request->getParsedBody();
            if (!isset($data['email']) || !isset($data['password'])) {
                throw new \InvalidArgumentException("Identifiants manquants.");
            }

            CsrfTokenProvider::check($data['csrf'] ?? '');

            $user = $this->authnProvider->signin($data['email'], $data['password']);
            $view = Twig::fromRequest($request);
            return $view->render($response, 'home.html.twig', [
                'message_accueil' => 'Bienvenue !',
                'user' => $_SESSION['user'] ?? null,
            ]);
        } catch (\Exception $e) {
            // Réponse simple en JSON avec le code et le message d’erreur
            $status = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 400;
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withStatus($status)->withHeader('Content-Type', 'application/json');
        }
    }
}
