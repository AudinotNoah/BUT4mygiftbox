<?php

declare(strict_types=1); 

namespace gift\core\application\usecases;

use gift\core\domain\exceptions\AuthnException;
use gift\core\domain\exceptions\UserNotFoundException;
use gift\core\domain\entities\User;

use Ramsey\Uuid\Uuid;

class AuthnService implements AuthnServiceInterface
{
    public function register(string $userId, string $password): array
    {
        if (User::where('user_id', $userId)->exists()) {
            throw new AuthnException("L'utilisateur avec l'ID fourni existe déjà.");
        }

        $user = User::create([
            'id' => Uuid::uuid4()->toString(), 
            'user_id' => $userId,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => '1'
        ]);
        
        if (!$user) {
            throw new AuthnException("Échec de la création de l'utilisateur.");
        }

        if (!$user->save()) {
            throw new AuthnException("Échec de la sauvegarde de l'utilisateur.");
        }

        return $user->toArray();
    }

    public function signin(string $userId, string $password): array
    {
        $user = User::where('user_id', $userId)->first();

        if (!$user || !password_verify($password, $user->password)) {
            throw new UserNotFoundException("Identifiants invalides.");
        }

        return $user->toArray();
    }
}