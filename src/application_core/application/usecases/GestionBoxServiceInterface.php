<?php

declare(strict_types=1);

namespace gift\core\application\usecases;

use gift\core\domain\exceptions\BoxNotFoundException;
use gift\core\domain\exceptions\PrestationNotFoundException;
use gift\core\domain\exceptions\CoffretTypeNotFoundException;
use gift\core\domain\exceptions\ValidationException; 
use gift\core\domain\exceptions\OperationNotPermittedException; 
use gift\core\domain\entities\Box;

interface GestionBoxServiceInterface
{

    public function creerBoxVide(string $userId, array $dataDonneesBox): array;

    public function creerBoxDepuisType(string $userId, int $coffretTypeId, array $dataDonneesBox): array;

    public function ajouterPrestationABox(string $boxId, string $prestationId, int $quantite): array;

    public function retirerPrestationDeBox(string $boxId, string $prestationId, string $userId): array;

    public function modifierQuantitePrestationDansBox(string $boxId, string $prestationId, int $nouvelleQuantite, string $userId): array;

    public function afficherBoxEnCours(string $boxId, string $userId): array;

    public function validerBox(string $boxId): array;

}