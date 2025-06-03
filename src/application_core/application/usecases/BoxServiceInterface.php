<?php

declare(strict_types=1);

namespace gift\core\application\usecases;

// Importer des exceptions métier si nécessaire
use gift\core\domain\exceptions\BoxNotFoundException;

interface BoxServiceInterface
{

    public function getBoxByToken(string $urlToken): array;
    public function getCurrentBox(): ?array;
    public function getPrestationsByBoxId(string $boxId): array;

}