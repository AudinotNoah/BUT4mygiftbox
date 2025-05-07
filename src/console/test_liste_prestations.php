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
            // Affichage ligne par ligne pour la console
            echo "ID        : " . $prestation->id . "\n";
            echo "Libellé   : " . $prestation->libelle . "\n";
            echo "Description: " . $prestation->description . "\n";
            echo "Tarif     : " . $prestation->tarif . " (" . $prestation->unite . ")\n";
            echo "------\n";
        }
    }

} catch (\Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}

