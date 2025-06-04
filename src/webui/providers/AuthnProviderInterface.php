<?php

declare(strict_types=1);

namespace gift\webui\providers;

use gift\core\application\usecases\AuthnServiceInterface;

interface AuthnProviderInterface
{
    public function getSignedInUser(): ?array;
    public function register(string $userId, string $password): array;
    public function signin(string $userId, string $password): array;
    public function logout(): void;
}