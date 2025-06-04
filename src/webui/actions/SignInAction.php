<?php

declare(strict_types=1);

namespace gift\webui\actions;

use gift\webui\providers\AuthnProviderInterface;
use gift\webui\providers\AuthnProvider;
use gift\webui\exceptions\ActionException;
use gift\webui\exceptions\InvalidRequestException;
use gift\webui\exceptions\UnauthorizedException;
use gift\webui\exceptions\ForbiddenException;
use gift\webui\exceptions\NotFoundException;
use gift\webui\exceptions\InternalServerErrorException;

use gift\webui\providers\CsrfTokenProvider;


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
                throw new InvalidRequestException("Identifiants manquants.");
            }

            try {
                CsrfTokenProvider::check($data['csrf'] ?? '');
            } catch (\Exception $e) {
                throw new InvalidRequestException("Token CSRF invalide ou manquant.", 0, $e);
            }

            $user = $this->authnProvider->signin($data['email'], $data['password']);
            $view = Twig::fromRequest($request);
            return $view->render($response, 'home.html.twig',
                [
                    'message_accueil' => 'Bienvenue !',
                    'user' => $_SESSION['user'] ?? null,
                ]
            );
        } catch (UnauthorizedException | ForbiddenException | NotFoundException | InternalServerErrorException | ActionException $e) {
            return $response->withStatus($e->getCode())->withJson(['error' => $e->getMessage()]);
        }
    }
}