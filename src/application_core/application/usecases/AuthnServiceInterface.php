<?php

declare(strict_types=1);

namespace gift\core\application\usecases;

interface AuthnServiceInterface
{
    public function register(string $userId, string $password): array;
    public function signin(string $userId, string $password): array;
}