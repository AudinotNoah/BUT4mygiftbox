<?php

declare(strict_types=1);

namespace gift\core\application\usecases;

use gift\core\application\usecases\BoxServiceInterface;
use gift\core\domain\entities\Box;
use gift\core\domain\exceptions\BoxNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException as EloquentModelNotFoundException;

class BoxService implements BoxServiceInterface
{
    public function getBoxByToken(string $urlToken): array
    {
        try {
 
            $box = Box::where('token', '=', $urlToken)
                        ->with('prestations') 
                        ->firstOrFail();
            $boxData = $box->toArray();

            return $boxData;

        } catch (EloquentModelNotFoundException $e) {
            throw new BoxNotFoundException("Box non trouvée ou inaccessible avec le token fourni.", 0, $e);
        }
    }

    
    public function getCurrentBoxByUserId(string $userId): ?array
    {
        $box = Box::where('createur_id', '=', $userId)
                  ->where('statut', '=', 1) // 1 = en cours de construction
                  ->with('prestations')
                  ->first();

        return $box ? $box->toArray() : null;
    }


    public function getPrestationsByBoxId(string $boxId): array
    {
        $box = Box::where('id', '=', $boxId)
                  ->with('prestations')
                  ->first();

        if (!$box) {
            throw new BoxNotFoundException("Box non trouvée avec l'ID fourni.");
        }

        return $box->prestations->toArray();
    }


    public function getBoxById(string $boxId): array
    {
        try {
            $box = Box::findOrFail($boxId);
            return $box->toArray();
        } catch (EloquentModelNotFoundException $e) {
            throw new BoxNotFoundException("Box avec l'id $boxId non trouvée.", 0, $e);
        }
    }
    


}