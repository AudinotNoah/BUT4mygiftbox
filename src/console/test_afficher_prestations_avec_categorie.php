<?php
require __DIR__ . '/../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;
use gift\appli\models\Prestation;

$config = parse_ini_file(__DIR__ . '/../conf/conf.ini');

$db = new DB;
$db->addConnection($config);
$db->setAsGlobal();
$db->bootEloquent();


try {
    $prestations = Prestation::all();

    if ($prestations->isEmpty()) {
        echo "Aucune prestation trouvée.\n";
    } else {
        foreach ($prestations as $prestation) {
            echo "Presta ID : " . $prestation->id . "\n";
            echo "Libellé   : " . $prestation->libelle . "\n";

            // Accès à la catégorie déclenche une requête séparée ICI pour chaque prestation !
            $categorie = $prestation->categorie;

            // Vérifier si la catégorie existe (au cas où cat_id serait invalide)
            if ($categorie) {
                echo "Catégorie : " . $categorie->libelle . " (ID: " . $categorie->id . ")\n";
            } else {
                echo "Catégorie : <non trouvée pour cat_id=" . $prestation->cat_id . ">\n";
            }
            echo "-----------------------------------------\n";
        }
    }
} catch (\Exception $e) {
    echo "Erreur (Lazy Loading) : " . $e->getMessage() . "\n";
}


echo "version eager loading\n-----------------------------------------\n";

try {
    $prestations = Prestation::with('categorie')->get();

    if ($prestations->isEmpty()) {
        echo "Aucune prestation trouvée.\n";
    } else {
        foreach ($prestations as $prestation) {
            echo "Presta ID : " . $prestation->id . "\n";
            echo "Libellé   : " . $prestation->libelle . "\n";

            $categorie = $prestation->categorie;

            if ($categorie) {
                echo "Catégorie : " . $categorie->libelle . " (ID: " . $categorie->id . ")\n";
            } else {
                echo "Catégorie : <non trouvée pour cat_id=" . $prestation->cat_id . ">\n";
            }
            echo "-----------------------------------------\n";
        }
    }
} catch (\Exception $e) {
    echo "Erreur (Eager Loading) : " . $e->getMessage() . "\n";
}
