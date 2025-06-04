<?php

declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use gift\webui\providers\AuthnProviderInterface;
use gift\webui\providers\AuthnProvider;
use Slim\Views\Twig;

use gift\webui\providers\CsrfTokenProvider;

class RegisterAction extends AbstractAction
{
    private AuthnProviderInterface $authnProvider;

    public function __construct()
    {
        $this->authnProvider = new AuthnProvider();
    }

    public function __invoke(Request $request, Response $response,array $args): Response
    {
        try {
            $data = $request->getParsedBody();
            if (!isset($data['email']) || !isset($data['password']) || !isset($data['cpassword'])) {
                throw new \Exception("Identifiants manquants.");
            }

            if ($data['password'] !== $data['cpassword']) {
                throw new \Exception("Les mots de passe ne correspondent pas.");
            }

            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL) === $data['email']) {
                throw new \Exception("Adresse e-mail invalide.");
            }

            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

            try {
                CsrfTokenProvider::check($data['csrf'] ?? '');
            } catch (\Exception $e) {
                throw new \Exception("Token CSRF invalide ou manquant.", 0, $e);
            }

            $user = $this->authnProvider->register($data['email'], $data['password']);
            $view = Twig::fromRequest($request);
            return $view->render($response, 'home.html.twig', [
                'message_accueil' => 'Bienvenue !',
                'user' => $_SESSION['user']['id'] ?? null,
            ]);
        } catch (UnauthorizedException | ForbiddenException | NotFoundException | InternalServerErrorException | ActionException $e) {
            return $response->withStatus($e->getCode())->withJson(['error' => $e->getMessage()]);
        }
    }
}