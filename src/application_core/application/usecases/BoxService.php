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
}