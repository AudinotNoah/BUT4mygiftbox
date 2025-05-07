<?php
require __DIR__ . '/../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;
use gift\appli\models\Categorie; 


$config = parse_ini_file(__DIR__ . '/../conf/conf.ini');

$db = new DB;
$db->addConnection($config);

$db->setAsGlobal();
$db->bootEloquent();


try {
    echo "Récupération des catégories...\n";
    $categories = Categorie::all();

    if ($categories->isEmpty()) {
        echo "\nAucune catégorie trouvée.\n";
    } else {
        echo "Liste des catégories :\n";
        foreach ($categories as $categorie) {
            echo "ID: " . $categorie->id . ", Libellé: " . $categorie->libelle . "\n";
        }
    }

} catch (\Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}