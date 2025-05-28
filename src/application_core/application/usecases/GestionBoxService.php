<?php

declare(strict_types=1);

namespace gift\core\application\usecases;

use gift\core\application\usecases\GestionBoxServiceInterface;
use gift\core\domain\exceptions\BoxNotFoundException;
use gift\core\domain\exceptions\PrestationNotFoundException;
use gift\core\domain\exceptions\CoffretTypeNotFoundException;
use gift\core\domain\exceptions\ValidationException; 
use gift\core\domain\exceptions\OperationNotPermittedException; 
use gift\core\domain\entities\Box;
use gift\core\application\usecases\CatalogueService;
use gift\core\application\usecases\BoxService;
use Ramsey\Uuid\Uuid;


class GestionBoxService implements GestionBoxServiceInterface
{

    private BoxServiceInterface $boxService;
    private CatalogueServiceInterface $catalogueService;

    public function __construct()
    {
        $this->boxService = new BoxService();
        $this->catalogueService = new CatalogueService();
    }

    function creerBoxVide(string $userId, array $dataDonneesBox): array {
        $box = new Box();

        $box->fill([
            'id' => Uuid::uuid4()->toString(),
            'token' => $dataDonneesBox['csrf'],
            'libelle' => $dataDonneesBox['libelle'] ?? '',
            'description' => $dataDonneesBox['description'] ?? '',
            'montant' => 0.0,
            'kdo' => $dataDonneesBox['isCadeau'],
            'message_kdo' => $dataDonneesBox['message_kdo'] ?? null,
            'createur_id' => $userId,
        ]);

        $box->save();
        return $box->toArray();
    }

    function ajouterPrestationABox(string $boxId, string $prestationId, int $quantite): array
    {
        $boxData = $this->boxService->getBoxById($boxId);
        $box = new Box();
        $box->fill($boxData);

        if ($quantite <= 0) {
            throw new ValidationException("La quantité doit être supérieure à zéro.");
        }

        $existingPrestation = $box->prestations()->where('id', $prestationId)->first();
        if ($existingPrestation) {
            $box->prestations()->updateExistingPivot($prestationId, ['quantite' => $existingPrestation->pivot->quantity + $quantite]);
        } else {
            $box->prestations()->attach($prestationId, ['quantite' => $quantite]);
        }

        return $box->toArray();
    }

    //fausse fonciton pour l'instant
    function retirerPrestationDeBox(string $boxId, string $prestationId, string $userId): array
    {
        $box = BoxService::getBoxById($boxId);
        if (!$box) {
            throw new BoxNotFoundException("Box avec l'id $boxId non trouvée.");
        }

        $prestation = CatalogueService::getPrestationById($prestationId);
        if (!$prestation) {
            throw new PrestationNotFoundException("Prestation avec l'id $prestationId non trouvée.");
        }

        $box->prestations()->detach($prestationId);

        return $box->toArray();
    }

    //fausse fonction pour l'instant
    function modifierQuantitePrestationDansBox(string $boxId, string $prestationId, int $nouvelleQuantite, string $userId): array
    {
        $box = BoxService::getBoxById($boxId);
        if (!$box) {
            throw new BoxNotFoundException("Box avec l'id $boxId non trouvée.");
        }

        $prestation = CatalogueService::getPrestationById($prestationId);
        if (!$prestation) {
            throw new PrestationNotFoundException("Prestation avec l'id $prestationId non trouvée.");
        }

        if ($nouvelleQuantite <= 0) {
            throw new ValidationException("La nouvelle quantité doit être supérieure à zéro.");
        }

        $box->prestations()->updateExistingPivot($prestationId, ['quantity' => $nouvelleQuantite]);

        return $box->toArray();
    }

    //fausse fonction pour l'instant
    function afficherBoxEnCours(string $boxId, string $userId): array
    {
        $box = BoxService::getBoxById($boxId);
        if (!$box) {
            throw new BoxNotFoundException("Box avec l'id $boxId non trouvée.");
        }

        return $box->toArray();
    }

    //fausse fonction pour l'instant
    function validerBox(string $boxId, string $userId): array
    {
        $box = BoxService::getBoxById($boxId);
        if (!$box) {
            throw new BoxNotFoundException("Box avec l'id $boxId non trouvée.");
        }

        if ($box->isValidated()) {
            throw new OperationNotPermittedException("La box est déjà validée.");
        }

        $box->validate();
        BoxService::saveBox($box);

        return $box->toArray();
    }

    //fausse fonction pour l'instant
    function creerBoxDepuisType(string $userId, int $coffretTypeId, array $dataDonneesBox): array
    {
        // Implémentation de la création d'une box depuis un type de coffret
        // Cette méthode doit être implémentée en fonction des spécificités du projet
        throw new \Exception("Cette méthode n'est pas encore implémentée.");
    }

}