<?php
require __DIR__ . '/../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;
use gift\appli\models\Categorie;
use Illuminate\Database\Eloquent\ModelNotFoundException;

$config = parse_ini_file(__DIR__ . '/../conf/conf.ini');

$db = new DB;
$db->addConnection($config);
$db->setAsGlobal();
$db->bootEloquent();

$categorieId = 3;

echo "--- Recherche Catégorie ID: $categorieId et ses Prestations ---\n";

try {
    $categorie = Categorie::findOrFail($categorieId);

    echo "Catégorie trouvée:\n";
    echo "ID        : " . $categorie->id . "\n";
    echo "Libellé   : " . $categorie->libelle . "\n";
    echo "Description: " . $categorie->description . "\n";
    echo "-- Prestations associées --\n";

    $prestations = $categorie->prestations;

    if ($prestations->isEmpty()) {
        echo "Aucune prestation associée à cette catégorie.\n";
    } else {
        foreach ($prestations as $prestation) {
            echo "  Presta ID: " . $prestation->id . "\n";
            echo "  Libellé  : " . $prestation->libelle . "\n";
            echo "  Tarif    : " . $prestation->tarif . "\n";
            echo "  ----------\n";
        }
    }

} catch (ModelNotFoundException $e) {
    echo "Erreur: Catégorie ID '$categorieId' non trouvée.\n";
} catch (\Exception $e) {
    echo "Erreur générale : " . $e->getMessage() . "\n";
}
