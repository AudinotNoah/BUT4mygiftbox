<?php

declare(strict_types=1);

namespace gift\webui\providers;

use gift\core\application\usecases\AuthnServiceInterface;
use gift\core\domain\exceptions\AuthnException;
use gift\core\domain\exceptions\UserNotFoundException;

class AuthnProvider implements AuthnProviderInterface
{
    private AuthnServiceInterface $authnService;

    public function __construct(AuthnServiceInterface $authnService)
    {
        $this->authnService = $authnService;
    }

    public function getSignedInUser(): ?array
    {
        if (!isset($_SESSION['user'])) {
            return null;
        }
        return $_SESSION['user'];
    }

    public function signin(string $userId, string $password): array
    {
        try {
            $userData = $this->authnService->signin($userId, $password);
            $_SESSION['user'] = $userData;
            return $userData;
        } catch (UserNotFoundException | AuthnException $e) {
            throw new \RuntimeException($e->getMessage(), 0, $e);
        }
    }
}