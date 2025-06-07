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
use gift\core\domain\entities\Prestation;
use Illuminate\Database\Eloquent\ModelNotFoundException;


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

        $token = random_bytes(32);
        $token = bin2hex($token);

        $box->fill([
            'id' => Uuid::uuid4()->toString(),
            'token' => $token,
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

    public function ajouterPrestationABox(string $boxId, string $prestationId, int $quantite, string $userId): array
    {
        try {
            $box = Box::findOrFail($boxId);
        } catch (ModelNotFoundException $e) {
            throw new BoxNotFoundException("Box avec l'id $boxId non trouvée.");
        }

        if ($box->createur_id !== $userId) {
            throw new OperationNotPermittedException("Vous n'êtes pas autorisé à modifier cette box.");
        }

        if ($box->statut !== 1) { 
            throw new ValidationException("Impossible d'ajouter une prestation à une box déjà validée.");
        }

        try {
            Prestation::findOrFail($prestationId);
        } catch (ModelNotFoundException $e) {
            throw new PrestationNotFoundException("Prestation avec l'id $prestationId non trouvée.");
        }

        if ($quantite <= 0) {
            throw new ValidationException("La quantité doit être supérieure à zéro.");
        }

        $prestationExistante = $box->prestations()->where('presta_id', $prestationId)->first();

        if ($prestationExistante) {
            $nouvelleQuantite = $prestationExistante->pivot->quantite + $quantite;
            $box->prestations()->updateExistingPivot($prestationId, ['quantite' => $nouvelleQuantite]);
        } else {
            $box->prestations()->attach($prestationId, ['quantite' => $quantite]);
        }

        $box->load('prestations');
        
        return $box->toArray();
    }

    function retirerPrestationDeBox(string $boxId, string $prestationId, string $userId): array
    {
        $box = Box::findOrFail($boxId);

        if ($box->createur_id !== $userId) {
            throw new OperationNotPermittedException("Action non autorisée.");
        }
        if ($box->statut !== 1) {
            throw new ValidationException("Impossible de modifier une box déjà validée.");
        }

        $result = $box->prestations()->detach($prestationId);

        if ($result === 0) {
            throw new PrestationNotFoundException("Cette prestation n'était pas dans la box.");
        }

        $box->load('prestations');
        return $box->toArray();
    }

    

    function modifierQuantitePrestationDansBox(string $boxId, string $prestationId, int $nouvelleQuantite, string $userId): array
    {
        $box = Box::findOrFail($boxId);

        if ($box->createur_id !== $userId) {
            throw new OperationNotPermittedException("Action non autorisée.");
        }
        if ($box->statut !== 1) {
            throw new ValidationException("Impossible de modifier une box déjà validée.");
        }
        
        if ($nouvelleQuantite <= 0) {
            return $this->retirerPrestationDeBox($boxId, $prestationId, $userId);
        }

        $result = $box->prestations()->updateExistingPivot($prestationId, ['quantite' => $nouvelleQuantite]);
        

        $box->load('prestations');
        return $box->toArray();
    }

    function afficherBoxEnCours(string $boxId, string $userId): array
    {
        $box = BoxService::getBoxById($boxId);
        if (!$box) {
            throw new BoxNotFoundException("Box avec l'id $boxId non trouvée.");
        }

        return $box->toArray();
    }

    public function validerBox(string $boxId, string $userId): array
    {
        $box = Box::with('prestations')->find($boxId);
        if (!$box) {
            throw new BoxNotFoundException("Box avec l'id $boxId non trouvée.");
        }

        
        if ($box->createur_id !== $userId) {
            throw new OperationNotPermittedException("Vous n'êtes pas autorisé à valider cette box.");
        }

        if ($box->prestations()->count() < 2) {
            throw new ValidationException("La box doit contenir au moins 2 prestations");
        }

        if ($box->statut !== 1) {
            throw new ValidationException("Cette box a déjà été validée ou est dans un état incorrect.");
        }

        $montantTotal = 0;
        foreach ($box->prestations as $prestation) {
            $montantTotal += $prestation->tarif * $prestation->pivot->quantite;
        }

        $box->statut = 2;
        $box->montant = $montantTotal;
        $box->save();

        return $box->toArray();
    }

    public function creerBoxDepuisType(string $userId, int $coffretTypeId, array $dataDonneesBox): array
    {
        $box = new Box();
        $token = bin2hex(random_bytes(32));

        $box->fill([
            'id' => Uuid::uuid4()->toString(),
            'token' => $token,
            'libelle' => $dataDonneesBox['libelle'],
            'description' => $dataDonneesBox['description'],
            'montant' => 0.0,
            'kdo' => $dataDonneesBox['isCadeau'],
            'message_kdo' => $dataDonneesBox['message_kdo'],
            'createur_id' => $userId,
            'statut' => 1,
        ]);
        $box->save();

        try {
            $coffretType = CoffretType::with('prestations')->findOrFail($coffretTypeId);
        } catch (ModelNotFoundException $e) {
            return $box->toArray();
        }

        $prestationIds = $coffretType->prestations->pluck('id')->all();
        if (!empty($prestationIds)) {

             $syncData = array_fill_keys($prestationIds, ['quantite' => 1]);
             $box->prestations()->syncWithoutDetaching($syncData);
        }
        
        $box->load('prestations');
        
        return $box->toArray();
    }

}