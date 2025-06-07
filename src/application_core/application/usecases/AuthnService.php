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
            throw new \Exception("L'utilisateur avec l'ID fourni existe déjà.");
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        if ($hashedPassword === false) {
            throw new \RuntimeException("Échec du hachage du mot de passe.");
        }

        $user = User::create([
            'id' => Uuid::uuid4()->toString(), 
            'user_id' => $userId,
            'password' => $hashedPassword,
            'role' => '1'
        ]);
        
        if (!$user) {
            throw new \Exception("Échec de la création de l'utilisateur.");
        }

        if (!$user->save()) {
            throw new \Exception("Échec de la sauvegarde de l'utilisateur.");
        }

        return $user->toArray();
    }

    public function signin(string $userId, string $password): array
    {
        $user = User::where('user_id', $userId)->first();

        if (!$user) {
            throw new \RuntimeException("Utilisateur introuvable");
        }
        if (!password_verify($password, $user->password)) {
            throw new AuthnException("Mot de passe incorrect.");
        }

        return $user->toArray();
    }
}