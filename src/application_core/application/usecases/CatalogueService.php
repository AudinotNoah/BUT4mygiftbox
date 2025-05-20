<?php

declare(strict_types=1);

namespace gift\core\application\usecases;

use gift\core\application\usecases\CatalogueServiceInterface;
use gift\core\domain\entities\Categorie;
use gift\core\domain\entities\Prestation;
use gift\core\domain\entities\Theme;
use gift\core\domain\entities\CoffretType;
use gift\core\domain\exceptions\CategorieNotFoundException;
use gift\core\domain\exceptions\PrestationNotFoundException;
use gift\core\domain\exceptions\CoffretTypeNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException as EloquentModelNotFoundException;

class CatalogueService implements CatalogueServiceInterface
{
    public function getCategories(): array
    {
        return Categorie::orderBy('libelle', 'asc')->get()->toArray();
    }

    public function getCategorieById(int $id): array
    {
        try {
            return Categorie::findOrFail($id)->toArray();
        } catch (EloquentModelNotFoundException $e) {
            throw new CategorieNotFoundException("Catégorie avec l'id $id non trouvée.", 0, $e);
        }
    }

    public function getPrestationById(string $id): array
    {
        try {
            return Prestation::findOrFail($id)->toArray();
        } catch (EloquentModelNotFoundException $e) {
            throw new PrestationNotFoundException("Prestation avec l'id $id non trouvée.", 0, $e);
        }
    }

    public function getPrestationsbyCategorie(int $categ_id): array
    {
        try {
            $categorie = Categorie::with('prestations')->findOrFail($categ_id);
            return $categorie->prestations->toArray();
        } catch (EloquentModelNotFoundException $e) {
            throw new CategorieNotFoundException("Catégorie avec l'id $categ_id non trouvée lors de la recherche de prestations.", 0, $e);
        }
    }

    public function getThemesCoffrets(): array
    {
        return Theme::with('coffretTypes.prestations')->orderBy('libelle')->get()->toArray();

    }

    public function getCoffretById(int $id): array
    {
        try {
            return CoffretType::with('prestations')->findOrFail($id)->toArray();
        } catch (EloquentModelNotFoundException $e) {
            throw new CoffretTypeNotFoundException("Coffret type avec l'id $id non trouvé.", 0, $e);
        }
    }
}