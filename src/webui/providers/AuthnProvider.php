<?php

declare(strict_types=1);

namespace gift\webui\providers;

use gift\core\application\usecases\AuthnServiceInterface;
use gift\core\application\usecases\AuthnService;
use gift\core\domain\exceptions\AuthnException;
use gift\core\domain\exceptions\UserNotFoundException;

class AuthnProvider implements AuthnProviderInterface
{
    private AuthnServiceInterface $authnService;

    public function __construct()
    {
        $this->authnService = new AuthnService();
    }

    public function getSignedInUser(): ?array
    {
        if (!isset($_SESSION['user'])) {
            return null;
        }
        return $_SESSION['user'];
    }

    public function register(string $userId, string $password): array
    {
        if(isset($_SESSION['user'])) {
            throw new \RuntimeException("User already registered in session.");
        }
        try {
            $userData = $this->authnService->register($userId, $password);
            $_SESSION['user'] = $userData;
            return $userData;
        } catch (AuthnException $e) {
            throw new \RuntimeException($e->getMessage(), 0, $e);
        }
    }

    public function signin(string $userId, string $password): array
    {
        if(isset($_SESSION['user'])) {
            throw new \RuntimeException("User already registered in session.");
        }
        try {
            $userData = $this->authnService->signin($userId, $password);
            $_SESSION['user'] = $userData;
            return $userData;
        } catch (UserNotFoundException | AuthnException $e) {
            throw new \RuntimeException($e->getMessage(), 0, $e);
        }
    }

    public function logout(): void
    {
        if (!isset($_SESSION['user'])) {
            throw new \RuntimeException("No user is currently signed in.");
        }
        unset($_SESSION['user']);
    }
}